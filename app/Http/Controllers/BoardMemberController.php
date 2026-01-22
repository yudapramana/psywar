<?php

namespace App\Http\Controllers;

use App\Models\BoardGroup;

class BoardMemberController extends Controller
{
    public function index()
    {
        $groups = BoardGroup::with([
            'members' => function ($q) {
                $q->whereNull('board_sub_section_id');
            },
            'subSections.members'
        ])->orderBy('order')->get();

        return view('pages.about.board-member', compact('groups'));
    }
}
