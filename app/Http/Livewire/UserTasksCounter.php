<?php

namespace App\Http\Livewire;

use Livewire\Component;

class UserTasksCounter extends Component
{
    public string $taskType;
    public int $userTasksCount;

    protected $listeners = ['userTasksCounterChange' => 'recalculateTask'];

    public function render()
    {
        return view('livewire.user-tasks-counter');
    }

    public function recalculateTask($plus = 1, $taskType = '')
    {
        if($this->taskType == $taskType){
            $this->userTasksCount += $plus;
        }
    }
}
