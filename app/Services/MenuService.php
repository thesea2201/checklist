<?php

namespace App\Services;

use App\Models\Checklist;
use App\Models\ChecklistGroup;
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
            'checklists.userTasks' => function ($query) {
                $query->whereNotNull('tasks.completed_at');
            }
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
                $checklist['completedTasksCount'] = count($checklist['user_tasks']);
            }
            $groups[] = $group;
        }

        return [
            'adminMenu' => $menu,
            'userMenu' => $groups
        ];
    }
}
