<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                {{ $checklist->name }}
            </div>

            <div class="card-body">
                <table class="table table-responsive-sm">
                    <thead>
                        @foreach ($checklist->tasks->where('user_id', null)->sortBy('position') as $task)
                            <tr>
                                <td>
                                    <input wire:click="completeTask({{ $task->id }})" type="checkbox" name=""
                                        id="checkbox-{{ $task->id }}" @if (in_array($task->id, $completedTasks)) checked="checked" @endif>
                                </td>
                                <td id="{{ $task->id }}" wire:click="toggleTask({{ $task->id }})"
                                    class="pointer">
                                    {{ $task->name }}</td>
                                <td>
                                    @if ($checklist->userTasks()->where('task_id', $task->id)->first()?->is_important)
                                        <a wire:click.prevent="makeAsImportant({{ $task->id }}, 1)"
                                            href="">&starf;</a>
                                    @else
                                        <a wire:click.prevent="makeAsImportant({{ $task->id }}, 1)"
                                            href="">&star;</a>
                                    @endif
                                </td>
                            </tr>
                            @if (isset($opened_tasks[$task->id]))
                                <tr>
                                    <td></td>
                                    <td colspan="2">{!! $task->description !!}</td>
                                </tr>
                            @endif
                        @endforeach

                    </thead>

                </table>
            </div>

        </div>
    </div>

    <div class="col-md-4">
        @if ($currentTask)
            <div class="card">
                <div class="card-header">
                    <strong>{{ $currentTask->name }}</strong>
                    <div class="float-right">
                        @if ($currentTask?->is_important)
                            <a wire:click.prevent="makeAsImportant({{ $currentTask->id }})" href="">&starf;</a>
                        @else
                            <a wire:click.prevent="makeAsImportant({{ $currentTask->id }})" href="">&star;</a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    @if ($currentTask->added_to_my_day_at == null)
                        <a href="#" wire:click="addToMyDay({{ $currentTask->id }})">&#9728;
                            {{ 'Add to my day' }}</a>
                    @else
                        <a href="#" wire:click="addToMyDay({{ $currentTask->id }})">&#9940;
                            {{ 'Remove from my day' }}</a>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="#">&#8986; {{ __('Remind me') }}</a>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            @if ($currentTask->due_date)
                                &#128197; Due {{ $currentTask->due_date->format('M j,Y') }}
                                <a href="" wire:click.prevent="setDueDate({{ $currentTask->id }})">{{ __('Remove') }}</a>
                            @else
                                <a href="" wire:click.prevent="toggleDueDate()">&#128197; {{ __('Add due date') }}</a>
                            @endif
                        </div>
                        @if ($isTonggleDueDate)
                            <ul>
                                <li>
                                    <a wire:click.prevent="setDueDate({{ $currentTask->id }}, '{{ today()->toDateString() }}')" href="">{{ __('Today') }}</a>
                                </li>
                                <li>
                                    <a wire:click.prevent="setDueDate({{ $currentTask->id }}, '{{ today()->addDay()->toDateString() }}')" href="">{{ __('Tomorrow') }}</a>
                                </li>
                                <li>
                                    <a wire:click.prevent="setDueDate({{ $currentTask->id }}, '{{ today()->addWeek()->toDateString() }}')" href="">{{ __('Next week') }}</a>
                                </li>

                                <li>
                                    {{ _('Or pick a date')}}
                                    <input class="form-control" type="date" name="due_date" wire:model="dueDate" value="{{today()->format('m-d-Y')}}">
                            </ul>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <a href="">&#9997; {{ 'Add note' }}</a>
                </div>
            </div>
        @endif
    </div>

</div>
