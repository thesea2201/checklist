<?php

namespace App\Http\Livewire;

use App\Models\Checklist;
use Livewire\Component;

class HeaderTotalsCount extends Component
{
    public $checklistGroupId;
    public $checklists;

    public function render()
    {
        $this->checklists = Checklist::where('checklist_group_id', $this->checklistGroupId)
                                    ->whereNull('user_id')
                                    ->withCount(['tasks' => function ($query) {
                                        $query->whereNull('tasks.user_id');
                                    }])
                                    ->withCount(['userTasks' => function ($query) {
                                        $query->whereNotNull('tasks.completed_at');
                                    }])
                                    ->get();
        return view('livewire.header-totals-count');
    }
}
