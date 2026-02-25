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
use Carbon\Carbon;

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
        $now = Carbon::now();

        // =========================
        // SUBMISSION STATUS (SAMA DENGAN HALAMAN PUBLIK)
        // =========================

        // return $now . ';;;;' . $event->submission_open_at;
        if (! $event->submission_open_at || ! $event->submission_deadline_at) {
            $submissionStatus = 'closed';
        } elseif ($now->lt($event->submission_open_at)) {
            $submissionStatus = 'not_open';
        } elseif ($now->gt($event->submission_deadline_at)) {
            $submissionStatus = 'closed';
        } else {
            // $now berada di antara open_at dan deadline_at (inclusive)
            $submissionStatus = 'open';
        }

        // return $submissionStatus;
        // =========================
        // LOCK JIKA BELUM / SUDAH TUTUP
        // =========================
        if ($submissionStatus === 'not_open') {
            return view('dashboard.submission.locked', compact(
                'event',
                'submissionStatus'
            ));
        }

        // =========================
        // HANYA PESERTA PAID
        // =========================
        $registration = Registration::where('participant_id', $participant->id)
            ->where('event_id', $event->id)
            ->where('status', 'paid')
            ->first();

        if (! $registration) {
            return view('dashboard.submission.package-required', compact(
                'event',
                'submissionStatus'
            ));
        }

        // =========================
        // DATA SUBMISSION
        // =========================
        $papers = Paper::with(['paperType', 'authors'])
            ->where('participant_id', $participant->id)
            ->latest()
            ->get();

        $submissionCount = $papers->count();

        return view('dashboard.submission.index', compact(
            'papers',
            'registration',
            'submissionCount',
            'event',
            'submissionStatus'
        ));
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

            // ✅ GOOGLE DRIVE LINK
            'gdrive_link' => [
                'required',
                'url',
                function ($attr, $value, $fail) {

                    // =========================
                    // 1️⃣ HOST WHITELIST
                    // =========================
                    $allowedHosts = [
                        'drive.google.com',
                        'docs.google.com',
                    ];

                    $host = parse_url($value, PHP_URL_HOST);

                    if (!$host || !in_array($host, $allowedHosts)) {
                        $fail('Only Google Drive links are allowed.');
                        return;
                    }

                    // =========================
                    // 2️⃣ WAJIB usp=sharing
                    // =========================
                    if (!str_contains($value, 'usp=sharing')) {
                        $fail('Please set access to "Anyone with the link (Viewer)".');
                        return;
                    }

                    // =========================
                    // 3️⃣ FORMAT FILE DRIVE (/file/d/)
                    // =========================
                    // Contoh valid:
                    // https://drive.google.com/file/d/FILE_ID/view?usp=sharing
                    if (!preg_match('#/file/d/[a-zA-Z0-9_-]+#', $value)) {
                        $fail('Invalid Google Drive file link format.');
                        return;
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
        // CREATE PAPER (DRAFT)
        // =========================
        $paper = Paper::create([
            'participant_id' => $participant->id,
            'paper_type_id'  => $validated['paper_type_id'],
            'title'          => $validated['title'],
            'abstract'       => $validated['abstract'],
            'gdrive_link'    => $validated['gdrive_link'],
            'file_type'      => str_contains($validated['gdrive_link'], '.pdf') ? 'pdf' : 'docx',
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
            ->route('dashboard.submission.show', $paper->uuid)
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
            ->route('dashboard.submission.edit', $paper->uuid)
            ->with('success', 'Draft updated successfully.');
    }


    

    public function download(Paper $paper)
    {
        $participant = auth()->user()->participant;
        abort_if($paper->participant_id !== $participant->id, 403);

        return redirect()->away($paper->gdrive_link);
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
