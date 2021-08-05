<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Models\Checklist;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Store
     *
     * @param StoreTaskRequest $request
     * @param Checklist $checklist
     * @return void
     */
    public function store(StoreTaskRequest $request, Checklist $checklist)
    {
        $position = $checklist->tasks()->max('position') + 1;
        $checklist->tasks()->create($request->validated() + ['position' => $position]);

        return redirect()->route('admin.checklist_groups.checklists.edit', [
            $checklist->checklist_group_id, $checklist
        ]);
    }

    /**
     * Show the form for editing the specified resource
     *
     * @param Checklist $checklist
     * @param Task $task
     * @return void
     */
    public function edit(Checklist $checklist, Task $task)
    {
        return view('admin.task.edit', compact('checklist', 'task'));
    }

    /**
     * Update the specified resource in storage
     *
     * @param StoreTaskRequest $request
     * @param Checklist $checklist
     * @param Task $task
     * @return void
     */
    public function update(StoreTaskRequest $request, Checklist $checklist, Task $task)
    {
        $task->update($request->validated());

        return redirect()->route('admin.checklist_groups.checklists.edit', [
            $checklist->checklist_group_id, $checklist
        ]);
    }

    /**
     * Remove the specified resource from storage
     *
     * @param Checklist $checklist
     * @param Task $task
     * @return void
     */
    public function destroy(Checklist $checklist, Task $task)
    {
        $task->delete();

        return redirect()->route('admin.checklist_groups.checklists.edit', [
            $checklist->checklist_group_id, $checklist
        ]);
    }

    public function reOrderPosition(Request $request)
    {
        $ids = $request->id;

        foreach ($ids as $order => $id) {
            $article = Task::findOrFail($id);
            $article->position = $order;
            $article->save();
        }
    }
}
