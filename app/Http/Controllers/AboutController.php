<?php

namespace App\Http\Controllers;

use App\Models\BoardGroup;
use App\Models\Gallery;

class AboutController extends Controller
{
    /**
     * About - Overview page
     */
    public function overview()
    {
        return view('pages.about.overview');
    }

    /**
     * About - Board Members page
     */
    public function boardMembers()
    {
        $boardGroups = BoardGroup::with([

                // ✅ Members TANPA sub section
                'members' => function ($q) {
                    $q->whereNull('board_sub_section_id')
                    ->orderBy('order')
                    ->orderBy('name');
                },

                // ✅ Members YANG PUNYA sub section
                'subSections.members' => function ($q) {
                    $q->orderBy('order')
                    ->orderBy('name');
                }

            ])
            ->orderBy('order')
            ->get();

        return view('pages.about.board-members', compact('boardGroups'));
    }

    /**
     * About - Galleries
     */
    public function galleries()
    {
        $galleries = Gallery::where('is_active', true)
            ->orderBy('order')
            ->latest()
            ->get();

        return view('pages.about.galleries', compact('galleries'));
    }

}
