<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Event;
use App\Models\Registration;
use App\Models\Paper;
use App\Models\PaperType;
use App\Models\PaperAuthor;
use Illuminate\Support\Str;
use ZipArchive;
use Symfony\Component\HttpFoundation\Response;

class SubmissionController extends Controller
{
    /**
     * Display submission page (list or locked state)
     */
    public function index()
    {
        $participant = auth()->user()->participant;
        abort_if(!$participant, 403, 'Participant profile not found.');

        $event = Event::where('is_active', true)->firstOrFail();

        // 🔒 Hanya peserta PAID
        $registration = Registration::where('participant_id', $participant->id)
            ->where('event_id', $event->id)
            ->where('status', 'paid')
            ->first();

        if (!$registration) {
            return view('dashboard.submission.locked');
        }

        $papers = Paper::with(['paperType', 'authors'])
            ->where('participant_id', $participant->id)
            ->latest()
            ->get();


        $submissionCount = $papers->count();

        return view('dashboard.submission.index', compact('papers', 'registration', 'submissionCount'));

    }

    /**
     * Show submission form
     */
    public function create()
    {
        $participant = auth()->user()->participant;
        abort_if(!$participant, 403);

        $event = Event::where('is_active', true)->firstOrFail();

        $registration = Registration::where('participant_id', $participant->id)
            ->where('event_id', $event->id)
            ->where('status', 'paid')
            ->first();

        if (!$registration) {
            return redirect()
                ->route('dashboard.submission.index')
                ->with('warning', 'You must complete registration payment before submitting.');
        }

        // 🔒 LIMIT 3 SUBMISSION PER PARTICIPANT
        $submissionCount = Paper::where('participant_id', $participant->id)->count();

        if ($submissionCount >= 3) {
            return redirect()
                ->route('dashboard.submission.index')
                ->with('warning', 'You have reached the maximum of 3 submissions.');
        }

        $paperTypes = PaperType::orderBy('name')->get();

        return view('dashboard.submission.create', compact('paperTypes'));
    }



    /**
     * Store submission (HARDENED)
     */
    public function store(Request $request)
    {
        $participant = auth()->user()->participant;
        abort_if(!$participant, 403);

        $event = Event::where('is_active', true)->firstOrFail();

        $registration = Registration::where('participant_id', $participant->id)
            ->where('event_id', $event->id)
            ->where('status', 'paid')
            ->first();

        if (!$registration) {
            return redirect()
                ->route('dashboard.submission.index')
                ->with('warning', 'Submission is only allowed after completing payment.');
        }

        // 🔒 LIMIT 3 SUBMISSIONS
        if (Paper::where('participant_id', $participant->id)->count() >= 3) {
            return redirect()
                ->route('dashboard.submission.index')
                ->with('warning', 'You have reached the maximum of 3 submissions.');
        }

        // =========================
        // VALIDATION (STABLE + SAFE)
        // =========================
        $validated = $request->validate([
            'paper_type_id' => 'required|exists:paper_types,id',
            'title'         => 'required|string|max:300',

            // 🔐 ABSTRACT MAX 300 WORDS
            'abstract' => [
                'required',
                function ($attr, $value, $fail) {
                    $wordCount = collect(
                        preg_split('/\s+/', trim(strip_tags($value)))
                    )->filter()->count();

                    if ($wordCount > 300) {
                        $fail("Abstract may not exceed 300 words. Current count: {$wordCount}.");
                    }
                }
            ],

            // 🔐 FILE VALIDATION (COMPATIBLE)
            'file' => [
                'required',
                'file',
                'max:10240', // 10 MB
                'mimes:pdf,doc,docx',
            ],

            'authors'                    => 'required|array|min:1',
            'authors.*.name'             => 'required|string|max:255',
            'authors.*.affiliation'      => 'nullable|string|max:255',
            'authors.*.is_corresponding' => 'nullable|boolean',
            'authors.*.is_presenting'    => 'nullable|boolean',
        ]);

        // =========================
        // FILE CONTENT VERIFICATION
        // =========================
        $file = $request->file('file');
        $mime = $file->getMimeType();

        $allowedMime = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];

        if (!in_array($mime, $allowedMime)) {
            return back()
                ->withErrors(['file' => 'Invalid or corrupted file detected.'])
                ->withInput();
        }

        // 🔐 PDF HEADER CHECK
        if ($mime === 'application/pdf') {
            $fh = fopen($file->getPathname(), 'rb');
            $header = fread($fh, 4);
            fclose($fh);

            if ($header !== '%PDF') {
                return back()
                    ->withErrors(['file' => 'Invalid PDF file structure.'])
                    ->withInput();
            }
        }

        // 🔐 DOCX ZIP STRUCTURE CHECK
        if ($mime === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
            $zip = new ZipArchive;

            if ($zip->open($file->getPathname()) !== true) {
                return back()
                    ->withErrors(['file' => 'Invalid DOCX file.'])
                    ->withInput();
            }

            if ($zip->locateName('word/document.xml') === false) {
                $zip->close();
                return back()
                    ->withErrors(['file' => 'Invalid DOCX structure.'])
                    ->withInput();
            }

            $zip->close();
        }

        // =========================
        // STORE FILE (PRIVATE DISK)
        // =========================
        $extension = strtolower($file->extension());
        $fileType  = $extension === 'pdf' ? 'pdf' : 'docx';

        $filename = 'paper_' .
            $participant->id . '_' .
            Str::uuid() . '.' .
            $extension;

        $path = $file->storeAs(
            'papers',
            $filename,
            'private' // 🔒 PRIVATE STORAGE
        );

        // =========================
        // CREATE PAPER (DRAFT)
        // =========================
        $paper = Paper::create([
            'participant_id' => $participant->id,
            'paper_type_id'  => $validated['paper_type_id'],
            'title'          => $validated['title'],
            'abstract'       => $validated['abstract'],
            'file_path'      => $path,
            'file_type'      => $fileType,
            'status'         => 'draft',
            'submitted_at'   => null,
        ]);

        // =========================
        // AUTHORS
        // =========================
        $hasCorresponding = false;

        foreach ($validated['authors'] as $i => $author) {
            $isCorresponding = isset($author['is_corresponding']);

            if ($isCorresponding) {
                $hasCorresponding = true;
            }

            PaperAuthor::create([
                'paper_id'         => $paper->id,
                'name'             => $author['name'],
                'affiliation'      => $author['affiliation'] ?? null,
                'is_corresponding' => $isCorresponding,
                'is_presenting'    => isset($author['is_presenting']),
                'order'            => $i + 1,
            ]);
        }

        // 🔒 FALLBACK: FIRST AUTHOR AS CORRESPONDING
        if (!$hasCorresponding) {
            PaperAuthor::where('paper_id', $paper->id)
                ->where('order', 1)
                ->update(['is_corresponding' => true]);
        }

        return redirect()
            ->route('dashboard.submission.index')
            ->with('success', 'Draft saved successfully. You can submit it when ready.');
    }





    /**
     * Show paper detail
     */
    public function show(Paper $paper)
    {
        $participant = auth()->user()->participant;
        abort_if($paper->participant_id !== $participant->id, 403);

        $paper->load(['paperType', 'authors']);

        return view('dashboard.submission.show', compact('paper'));
    }

    /**
     * Delete submission
     */
    public function destroy(Paper $paper)
    {
        $participant = auth()->user()->participant;
        abort_if($paper->participant_id !== $participant->id, 403);

        // 🔒 Tidak boleh hapus jika sudah direview
        if (!in_array($paper->status, ['draft', 'submitted'])) {
            return back()->with('warning', 'This submission can no longer be deleted.');
        }

        if ($paper->file_path) {
            Storage::disk('public')->delete($paper->file_path);
        }

        $paper->delete();

        return redirect()
            ->route('dashboard.submission.index')
            ->with('success', 'Submission deleted successfully.');
    }

    public function submit(Paper $paper)
    {
        $participant = auth()->user()->participant;
        abort_if($paper->participant_id !== $participant->id, 403);

        abort_if($paper->status !== 'draft', 403, 'Only draft can be submitted.');

        $paper->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        return redirect()
            ->route('dashboard.submission.show', $paper->id)
            ->with('success', 'Paper successfully submitted.');
    }

    public function edit(Paper $paper)
    {
        $participant = auth()->user()->participant;
        abort_if($paper->participant_id !== $participant->id, 403);
        abort_if($paper->status !== 'draft', 403);

        $paperTypes = PaperType::orderBy('name')->get();
        $paper->load('authors');

        return view('dashboard.submission.edit', compact('paper', 'paperTypes'));
    }


    public function update(Request $request, Paper $paper)
    {
        $participant = auth()->user()->participant;
        abort_if($paper->participant_id !== $participant->id, 403);

        // 🔒 LOCK jika bukan draft
        if ($paper->status !== 'draft') {
            return back()->with('warning', 'This submission can no longer be edited.');
        }

        // ✅ VALIDATION
        $validated = $request->validate([
            'paper_type_id' => 'required|exists:paper_types,id',
            'title'         => 'required|string|max:300',

            'abstract' => [
                'required',
                function ($attr, $value, $fail) {
                    $wordCount = collect(
                        preg_split('/\s+/', trim(strip_tags($value)))
                    )->filter()->count();

                    if ($wordCount > 300) {
                        $fail("Abstract may not exceed 300 words. Current count: {$wordCount}.");
                    }
                }
            ],

            'authors'                    => 'required|array|min:1',
            'authors.*.name'             => 'required|string|max:255',
            'authors.*.affiliation'      => 'nullable|string|max:255',
            'authors.*.is_corresponding' => 'nullable|boolean',
            'authors.*.is_presenting'    => 'nullable|boolean',
        ]);

        // =========================
        // 📝 UPDATE PAPER
        // =========================
        $paper->update([
            'paper_type_id' => $validated['paper_type_id'],
            'title'         => $validated['title'],
            'abstract'      => $validated['abstract'],
        ]);

        // =========================
        // 🔁 REPLACE AUTHORS
        // =========================
        $paper->authors()->delete();

        // ⛔ hanya 1 corresponding author
        if (collect($validated['authors'])
            ->filter(fn ($a) => isset($a['is_corresponding']))
            ->count() > 1) {

            return back()
                ->withErrors(['authors' => 'Only one corresponding author is allowed.'])
                ->withInput();
        }

        $hasCorresponding = false;

        foreach ($validated['authors'] as $index => $author) {

            $isCorresponding = isset($author['is_corresponding']);
            $isPresenting    = isset($author['is_presenting']);

            if ($isCorresponding) {
                $hasCorresponding = true;
            }

            PaperAuthor::create([
                'paper_id'         => $paper->id,
                'name'             => $author['name'],
                'affiliation'      => $author['affiliation'] ?? null,
                'is_corresponding' => $isCorresponding,
                'is_presenting'    => $isPresenting,
                'order'            => $index + 1,
            ]);
        }

        // 🔒 FALLBACK
        if (!$hasCorresponding) {
            PaperAuthor::where('paper_id', $paper->id)
                ->where('order', 1)
                ->update(['is_corresponding' => true]);
        }

        return redirect()
            ->route('dashboard.submission.edit', $paper->id)
            ->with('success', 'Draft updated successfully.');
    }


    

    public function download(Paper $paper)
    {
        $participant = auth()->user()->participant;

        // =========================
        // 🔒 AUTHORIZATION (IDOR PROTECTION)
        // =========================
        abort_if(
            !$participant || $paper->participant_id !== $participant->id,
            Response::HTTP_FORBIDDEN
        );

        // =========================
        // 🔍 FILE EXISTENCE CHECK
        // =========================
        if (!Storage::disk('private')->exists($paper->file_path)) {
            abort(404, 'File not found.');
        }

        // =========================
        // 🧼 SAFE FILENAME
        // =========================
        $safeFilename = Str::slug($paper->title);
        $extension    = $paper->file_type;

        $downloadName = "{$safeFilename}.{$extension}";

        // =========================
        // ⬇️ DOWNLOAD (STREAMED)
        // =========================
        return Storage::disk('private')->download(
            $paper->file_path,
            $downloadName
        );
    }


   

    public function previewPdf(Paper $paper)
    {
        $participant = auth()->user()->participant;

        // =========================
        // 🔒 AUTHORIZATION (IDOR SAFE)
        // =========================
        abort_if(
            !$participant || $paper->participant_id !== $participant->id,
            Response::HTTP_FORBIDDEN
        );

        // =========================
        // 🧪 PDF ONLY
        // =========================
        abort_if(
            $paper->file_type !== 'pdf',
            404,
            'Preview only available for PDF files.'
        );

        // =========================
        // 🔍 FILE EXISTS
        // =========================
        if (!Storage::disk('private')->exists($paper->file_path)) {
            abort(404, 'File not found.');
        }

        // =========================
        // 📄 STREAM PDF (SECURE HEADERS)
        // =========================
        $stream = Storage::disk('private')->readStream($paper->file_path);

        return response()->stream(function () use ($stream) {
            fpassthru($stream);
        }, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="preview.pdf"',

            // 🔐 SECURITY HEADERS
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options'        => 'SAMEORIGIN',
            'Referrer-Policy'        => 'no-referrer',
            'Cache-Control'          => 'private, no-store, no-cache, must-revalidate',
            'Pragma'                 => 'no-cache',
        ]);
    }







}
