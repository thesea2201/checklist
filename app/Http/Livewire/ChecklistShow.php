<?php

namespace App\Http\Livewire;

use App\Models\Task;
use Livewire\Component;

class ChecklistShow extends Component
{
    public $checklist;
    public $opened_tasks = [];
    public $completedTasks = [];
    public ?Task $currentTask;
    public $isTonggleDueDate = false;
    public $dueDate;

    public function mount()
    {
        $this->completedTasks = Task::where('checklist_id', $this->checklist->id)
            ->where('user_id', auth()->user()->id)
            ->whereNotNull('completed_at')
            ->get()
            ->pluck('task_id')
            ->toArray();

        $this->currentTask = NULL;
    }

    public function render()
    {
        return view('livewire.checklist-show');
    }

    public function toggleTask($taskId)
    {
        if (isset($this->opened_tasks[$taskId])) {
            unset($this->opened_tasks[$taskId]);
            $this->currentTask = NULL;
        } else {
            $this->opened_tasks = [];
            $this->opened_tasks[$taskId] = $taskId;
            $this->currentTask = Task::where('task_id', $taskId)->where('user_id', auth()->id())->first();

            if (!$this->currentTask) {
                $task = Task::find($taskId);
                $this->replicateTaskForUser($task);
            }
        }
    }

    public function completeTask($taskId)
    {
        $task = Task::find($taskId);

        if (is_null($task)) {
            return;
        }

        $userTask = Task::where('task_id', $taskId)->where('user_id', auth()->id())->first();
        if ($userTask) {
            if ($userTask->completed_at == NULL) {
                $userTask->update(['completed_at' => now()]);
                $this->emitCompletedTask(1, $task->checklist_id);
            } else {
                $userTask->update(['completed_at' => NULL]);
                $this->emitCompletedTask(-1, $task->checklist_id);
            }

            return;
        }

        $replicatedTask = $this->replicateTaskForUser($task);
        $replicatedTask->update(['completed_at' => now()]);

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

    private function replicateTaskForUser($task)
    {
        $newTask = $task->replicate();
        $newTask->user_id = auth()->user()->id;
        $newTask->task_id = $task->id;
        $newTask->save();

        return $newTask;
    }

    public function addToMyDay($taskId)
    {
        $userTask = Task::find($taskId);

        if (is_null($userTask)) {
            return;
        }
        if ($userTask) {
            if ($userTask->added_to_my_day_at == NULL) {
                $userTask->update(['added_to_my_day_at' => now()]);
                $this->emitUserTasksCounterChange(1, 'myDay');
            } else {
                $userTask->update(['added_to_my_day_at' => NULL]);
                $this->emitUserTasksCounterChange(-1, 'myDay');
            }
            $this->currentTask = $userTask;

            return;
        }
    }

    private function emitUserTasksCounterChange($plus = 1, $taskType = null)
    {
        $this->emit(
            'userTasksCounterChange',
            $plus,
            $taskType
        );
    }

    public function makeAsImportant($taskId, $isAdminTask = 0)
    {
        $task = Task::find($taskId);

        if ($isAdminTask) {
            $userTask = Task::where('task_id', $taskId)
                ->where('user_id', auth()->id(0))->first();
        } else {
            $userTask = $task;
        }

        if (is_null($task)) {
            return;
        }
        if ($userTask) {
            if ($userTask->is_important == 0) {
                $userTask->update(['is_important' => 1]);
                $this->emitUserTasksCounterChange(1, 'important');
            } else {
                $userTask->update(['is_important' => 0]);
                $this->emitUserTasksCounterChange(-1, 'important');
            }
            $this->currentTask = $userTask;

            return;
        }

        $replicatedTask = $this->replicateTaskForUser($task);
        $replicatedTask->update(['is_important' => 1]);

        $this->emitUserTasksCounterChange(1, 'important');
    }

    public function setDueDate($taskId, $dueDate = NULL)
    {
        $task = Task::find($taskId);

        if (!$task) {
            return;
        }

        $task->update(['due_date' => $dueDate]);

        $this->currentTask = $task;
        if ($dueDate == NULL) {
            $this->isTonggleDueDate = true;
            $this->emitUserTasksCounterChange(-1, 'planed');
        } else{
            $this->isTonggleDueDate = false;
            $this->emitUserTasksCounterChange(1, 'planed');
        }
    }

    public function toggleDueDate()
    {
        $this->isTonggleDueDate = !$this->isTonggleDueDate;
    }

    public function updatedDueDate($value)
    {
        $this->setDueDate($this->currentTask->id, $value);
    }
}
