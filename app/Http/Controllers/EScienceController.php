<?php

namespace App\Http\Controllers;

use App\Models\Paper;
use App\Models\PaperType;

class EScienceController extends Controller
{
    /**
     * Abstracts & Cases Submission (info page)
     */
    public function submission()
    {
        return view('pages.escience.abstract-case-submission');
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
