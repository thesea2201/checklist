<?php

namespace App\Services;

use App\Models\Checklist;
use App\Models\ChecklistGroup;
use App\Models\Task;
use Illuminate\Support\Carbon;

class MenuService
{
    public function getMenu(): array
    {
        $menu = ChecklistGroup::with([
            'checklists' => function ($query) {
                $query->whereNull('user_id');
            },
            'checklists.tasks' => function ($query) {
                $query->whereNull('tasks.user_id');
            },
            'checklists.userCompletedTasks'
        ])->get();

        $groups = [];
        $last_action_at = auth()->user()->last_action_at;
        if ($last_action_at == null) {
            $last_action_at = now()->subYears(10);
        }

        foreach ($menu->toArray() as $group) {
            $group['is_new'] = Carbon::create($group['created_at'])->greaterThan($last_action_at);
            $group['is_updated'] = (!$group['is_new']) && Carbon::create($group['updated_at'])->greaterThan($last_action_at);
            foreach ($group['checklists'] as &$checklist) {
                $checklist['is_new'] = (!$group['is_new']) && Carbon::create($checklist['created_at'])->greaterThan($last_action_at);
                $checklist['is_updated'] = (!$group['is_new'] && !$group['is_updated']) && (!$checklist['is_new']) && Carbon::create($checklist['updated_at'])->greaterThan($last_action_at);
                $checklist['tasksCount'] = count($checklist['tasks']);
                $checklist['completedTasksCount'] = count($checklist['user_completed_tasks']);
            }
            $groups[] = $group;
        }

        $userTasksMenu = [];
        if(!auth()->user()->is_admin){
            $userTasks = Task::where('user_id', auth()->id())->get();
            $userTasksMenu = [
                'myDay' => [
                    'name' => 'My day',
                    'icon' => 'cil-sun',
                    'tasksCount' => $userTasks->whereNotNull('added_to_my_day_at')->count()
                ],
                'important' => [
                    'name' => 'Important',
                    'icon' => 'cil-star',
                    'tasksCount' => $userTasks->where('is_important', 1)->count()
                ],
                'planed' => [
                    'name' => 'Planed',
                    'icon' => 'cil-calendar',
                    'tasksCount' => $userTasks->whereNotNull('due_date')->count()
                ]
                ];
        }

        return [
            'adminMenu' => $menu,
            'userMenu' => $groups,
            'userTasksMenu' => $userTasksMenu
        ];
    }
}
