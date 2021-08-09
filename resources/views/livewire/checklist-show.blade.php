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
                                <td wire:click="toggleTask({{ $task->id }})" class="pointer">
                                    <svg id="task-caret-up-{{ $task->id }}" class="c-sidebar-nav-icon d-none">
                                        <use
                                            xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-chevron-circle-up-alt                                            ') }}">
                                        </use>
                                    </svg>
                                    <svg id="task-caret-down-{{ $task->id }}" class="c-sidebar-nav-icon">
                                        <use
                                            xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-chevron-circle-down-alt                                            ') }}">
                                        </use>
                                    </svg>
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
                        <a href="">&star;</a>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    @if ($currentTask->added_to_my_day_at == NULL)
                        <a href="#" wire:click="addToMyDay({{$currentTask->id}})">&#9728; {{ 'Add to my day' }}</a>
                    @else
                        <a href="#" wire:click="addToMyDay({{$currentTask->id}})">&#9940; {{ 'Remove from my day' }}</a>
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
                            <a href="#">&#128197; {{ __('Add due date') }}</a>
                        </div>
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
