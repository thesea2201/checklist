<?php

namespace App\Http\Livewire;

use App\Models\Task;
use Livewire\Component;

class ChecklistShow extends Component
{
    public $checklist;
    public $opened_tasks = [];
    public $completedTasks = [];

    public function mount()
    {
        $this->completedTasks = Task::where('checklist_id', $this->checklist->id)
            ->where('user_id', auth()->user()->id)
            ->whereNotNull('completed_at')
            ->get()
            ->pluck('task_id')
            ->toArray();
    }

    public function render()
    {
        return view('livewire.checklist-show');
    }

    public function toggleTask($task_id)
    {
        if (isset($this->opened_tasks[$task_id])) {
            unset($this->opened_tasks[$task_id]);
        } else {
            $this->opened_tasks[$task_id] = $task_id;
        }
    }

    public function completeTask($task_id)
    {
        $task = Task::find($task_id);

        if (is_null($task)) {
            return;
        }

        $user_task = Task::where('task_id', $task_id)->where('user_id', auth()->id())->first();
        if ($user_task) {
            if ($user_task->completed_at == NULL) {
                $user_task->update(['completed_at' => now()]);
                $this->emitCompletedTask(1, $task->checklist_id);
            } else {
                $user_task->update(['completed_at' => NULL]);
                $this->emitCompletedTask(-1, $task->checklist_id);
            }

            return;
        }

        $new_task = $task->replicate();
        $new_task->user_id = auth()->user()->id;
        $new_task->task_id = $task_id;
        $new_task->completed_at = now();
        $new_task->save();

        $this->emitCompletedTask(1, $task->checklist_id);
    }

    private function emitCompletedTask($plus = 1, $checklistId = null)
    {
        $this->emit(
            'taskCoplete',
            $plus,
            $checklistId
        );
    }
}
