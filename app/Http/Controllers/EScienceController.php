<?php

namespace App\Http\Controllers;

use App\Models\Paper;
use App\Models\PaperType;
use App\Models\Event;
use Carbon\Carbon;

class EScienceController extends Controller
{
    /**
     * Abstracts & Cases Submission (info page)
     */
    public function submission()
    {
        // Ambil event aktif (SYMCARD 2026)
        $event = Event::where('is_active', true)->firstOrFail();

        $now = Carbon::now();

        // Tentukan status submission
        if (! $event->submission_is_active) {
            $submissionStatus = 'closed';
        } elseif ($now->lt($event->submission_open_at)) {
            $submissionStatus = 'not_open';
        } elseif ($now->between($event->submission_open_at, $event->submission_deadline_at)) {
            $submissionStatus = 'open';
        } else {
            $submissionStatus = 'closed';
        }

        return view('pages.escience.abstract-case-submission', compact(
            'event',
            'submissionStatus'
        ));
    }

    /**
     * Accepted Research & Case (public list)
     */
    public function accepted()
    {
        $researchType = PaperType::where('code', 'RESEARCH')->first();
        $caseType     = PaperType::where('code', 'CASE')->first();

        $baseQuery = Paper::with([
                'paperType',
                'authors' => fn ($q) => $q
                    ->where('is_presenting', true)
                    ->orderBy('order')
            ])
            ->where('status', 'accepted')
            ->whereNotNull('final_status')
            ->orderBy('final_status')
            ->orderBy('title');

        $researchPapers = $researchType
            ? (clone $baseQuery)
                ->where('paper_type_id', $researchType->id)
                ->paginate(10, ['*'], 'research')
            : collect();

        $casePapers = $caseType
            ? (clone $baseQuery)
                ->where('paper_type_id', $caseType->id)
                ->paginate(10, ['*'], 'case')
            : collect();

        return view('pages.escience.accepted-research-case', compact(
            'researchPapers',
            'casePapers'
        ));
    }
}
