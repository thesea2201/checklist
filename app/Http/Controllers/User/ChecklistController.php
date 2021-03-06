<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Checklist;
use App\Services\ChecklistService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChecklistController extends Controller
{
    public function show(Checklist $checklist)
    {
        //Sync checklist from admin
        (new ChecklistService())->sync_checklist($checklist, Auth::id());

        return view('user.checklist.show', compact('checklist'));
    }

    public function taskList(string $listType)
    {
        return view('user.checklist.task-list', compact('listType'));
    }
}
