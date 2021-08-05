<?php

namespace App\Http\Livewire;

use Livewire\Component;

class CompletedTasksCounter extends Component
{
    public $tasksCount;
    public $completedTasksCount;
    public $checklistId;

    protected $listeners = ['taskCoplete' => 'recaculateCompletedTasks'];

    public function render()
    {
        return view('livewire.completed-tasks-counter');
    }

    public function recaculateCompletedTasks($plus, $checklist_id)
    {
        if($this->checklistId == $checklist_id){
            $this->completedTasksCount += $plus;
        }
    }
}
