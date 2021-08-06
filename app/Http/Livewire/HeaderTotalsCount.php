<?php

namespace App\Http\Livewire;

use App\Models\Checklist;
use Livewire\Component;

class HeaderTotalsCount extends Component
{
    public $checklistGroupId;
    public $checklists;

    protected $listeners = ['taskCoplete' => 'recaculateCompletedTasks'];

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

    public function recaculateCompletedTasks($plus, $checklist_id)
    {
        foreach ($this->checklists as $key => $checklist) {
            if($checklist->id == $checklist_id){
                $checklist->user_tasks_count += $plus;
            }
        }
    }
}
