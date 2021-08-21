<?php

namespace App\Http\Livewire;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ChecklistShow extends Component
{
    public $checklist;
    public $listType;
    public $listName;
    public $listTasks = [];
    public $userTasks = [];

    public $opened_tasks = [];
    public $completedTasks = [];
    public ?Task $currentTask;
    public $isTonggleDueDate = false;
    public $isTonggleNote = false;
    public $dueDate;
    public $note;
    public $isTonggleReminder = false;
    public $reminderDate;
    public $reminderHour;

    public function mount()
    {
        $this->currentTask = NULL;

        $this->dueDate = now()->toDateString();
        $this->reminderDate = now()->addDay()->toDateString();
        $this->reminderHour = now()->hour;
    }

    public function render()
    {
        if (is_null($this->listType)) {
            $this->listName = $this->checklist->name;
            $this->listTasks = $this->checklist->tasks->where('user_id', null)->sortBy('position');
            $this->userTasks = $this->checklist->userTasks()->get()->sortBy('position');
            $this->completedTasks = $this->checklist->userCompletedTasks()->pluck('task_id')->toArray();
        } else {
            switch ($this->listType) {
                case 'myDay':
                    $this->listName = __('My day');
                    $this->userTasks = Task::where('user_id', auth()->id())->whereNotNull('added_to_my_day_at')->get();
                    break;

                case 'important':
                    $this->listName = __('Important');
                    $this->userTasks = Task::where('user_id', auth()->id())->where('is_important', 1)->get();
                    break;

                case 'planed':
                    $this->listName = __('Planed');
                    $this->userTasks = Task::where('user_id', auth()->id())->whereNotNull('due_date')->get();
                    break;

                default:
                    abort(404);
                    break;
            }

            $this->listTasks = Task::whereIn('id', $this->userTasks->pluck('task_id')->toArray())->get();
            $this->completedTasks = $this->userTasks->whereNotNull('completed_at')->pluck('task_id')->toArray();
        }

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
                $userTask = $this->replicateTaskForUser($task);
                $this->currentTask = $userTask;
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
        $this->currentTask = $userTask;

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
        } else {
            $this->isTonggleDueDate = false;
            $this->emitUserTasksCounterChange(1, 'planed');
        }
    }

    public function toggleDueDate()
    {
        $this->isTonggleDueDate = !$this->isTonggleDueDate;
    }

    public function toggleNote()
    {
        $this->isTonggleNote = !$this->isTonggleNote;
        $this->note = $this->currentTask->note;
    }

    public function saveNotes($taskId)
    {
        $task = Task::find($taskId);
        if ($task) {
            $task->update(['note' => $this->note]);
            $this->currentTask = $task;
            $this->isTonggleNote = false;
        }
    }

    public function toggleReminder()
    {
        $this->isTonggleReminder = !$this->isTonggleReminder;
        // $this->reminderDate = $this->currentTask->reminder_at->toDateString();
        // $this->reminderHour = $this->currentTask->reminder_at->toDateString();
    }

    public function setReminder($taskId, $reminderDate = null)
    {
        $task = Task::find($taskId);
        if ($task) {
            if ($reminderDate == null) {
                $reminderAt = null;
                $this->isTonggleReminder = true;
            } else {
                $reminderAt = Carbon::create($reminderDate)
                    ->setHour($this->reminderHour ?? now()->hour);
                $this->isTonggleReminder = false;
            }

            $task->update(['reminder_at' => $reminderAt]);
            $this->currentTask = $task;
        }
    }
}
