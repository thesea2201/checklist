<?php

namespace App\Http\View\Composers;

use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\View\View;

class MenuComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $menu = \App\Models\ChecklistGroup::with([
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

        $view->with('admin_menu', $menu);

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

        $view->with('user_menu', $groups);
    }
}
