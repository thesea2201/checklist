<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                {{ $listName }}
            </div>

            <div class="card-body">
                @if (count($userTasks))
                    <table class="table table-responsive-sm">
                        <thead>
                            @foreach ($listTasks as $task)
                                <tr>
                                    <td>
                                        <input wire:click="completeTask({{ $task->id }})" type="checkbox" name=""
                                            id="checkbox-{{ $task->id }}" @if (in_array($task->id, $completedTasks)) checked="checked" @endif>
                                    </td>
                                    <td id="{{ $task->id }}" wire:click="toggleTask({{ $task->id }})"
                                        class="pointer">
                                        {{ $task->name }}</td>
                                    <td>
                                        @if ($userTasks->where('task_id', $task->id)->first()?->is_important)
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
                @else
                    {{ __('No task found') }}
                @endif

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
                            {{ 'Add to My day' }}</a>
                    @else
                        <a href="#" style="color: red" wire:click="addToMyDay({{ $currentTask->id }})">&#9940;
                            {{ 'Remove from My day' }}</a>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            @if ($currentTask->reminder_at)
                                &#8986; {{ __('Reminder me at: ') }}
                                <br>
                                <b>{{ $currentTask->reminder_at->format('M j,Y, G:i') }}</b>
                                <a href="" style="color: red"
                                    wire:click.prevent="setReminder({{ $currentTask->id }})">{{ __('Remove') }}</a>
                            @else
                                &#8986; <a wire:click.prevent="toggleReminder()" href="">{{ __('Reminder me') }}</a>
                            @endif
                        </div>
                        @if ($isTonggleReminder)
                            </li>
                            <ul>
                                <li>
                                    <a wire:click.prevent="setReminder({{ $currentTask->id }}, '{{ today()->addDay()->toDateString() }}')"
                                        href="">{{ __('Tomorrow at: ') }} {{ date('H') }}:00</a>
                                </li>
                                <li>
                                    <a wire:click.prevent="setReminder({{ $currentTask->id }}, '{{ today()->addWeek()->startOfWeek()->toDateString() }}')"
                                        href="">{{ __('Next Monday at: ') }} {{ date('H') }}:00</a>
                                </li>
                                <li>
                                    {{ __('Or pick a date and hour') }}
                                    <div class="row">
                                        <div class="col-12">
                                            <input class="form-control" type="date" wire:model="reminderDate"
                                                name="reminder_at" id="reminder_at">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-8">
                                            <select class="form-control" id="ccmonth" wire:model="reminderHour">
                                                @foreach (range(0, 23) as $hour)
                                                    <option value="{{ $hour }}" @if ($hour == date('H')) selected @endif>{{ $hour }}:00</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-4">
                                            <button class="btn btn-primary" type="submit"
                                                wire:click="setReminder({{ $currentTask->id }}, '{{ $reminderDate }}')">{{ __('Save') }}</button>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        @endif
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            @if ($currentTask->due_date)
                                &#128197; Due <br>
                                <b>{{ $currentTask->due_date->format('M j,Y') }}</b>
                                <a href="" style="color: red"
                                    wire:click.prevent="setDueDate({{ $currentTask->id }})">{{ __('Remove') }}</a>
                            @else
                                <a href="" wire:click.prevent="toggleDueDate()">&#128197;
                                    {{ __('Add due date') }}</a>
                            @endif
                        </div>
                        @if ($isTonggleDueDate)
                            <ul>
                                <li>
                                    <a wire:click.prevent="setDueDate({{ $currentTask->id }}, '{{ today()->toDateString() }}')"
                                        href="">{{ __('Today') }}</a>
                                </li>
                                <li>
                                    <a wire:click.prevent="setDueDate({{ $currentTask->id }}, '{{ today()->addDay()->toDateString() }}')"
                                        href="">{{ __('Tomorrow') }}</a>
                                </li>
                                <li>
                                    <a wire:click.prevent="setDueDate({{ $currentTask->id }}, '{{ today()->addWeek()->toDateString() }}')"
                                        href="">{{ __('Next week') }}</a>
                                </li>

                                <li>
                                    {{ _('Or pick a date') }}
                                    <div class="row">
                                        <div class="col-12">
                                            <input class="form-control" type="date" wire:model="dueDate" name="due_date"
                                                id="due_date">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <button class="btn btn-primary" type="submit"
                                                wire:click="setDueDate({{ $currentTask->id }}, '{{ $dueDate }}')">{{ __('Save') }}</button>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <p>&#9997; {{ 'Notes' }}</p>
                    @if (!$isTonggleNote)
                        <div id="note-view" class="mt-4" wire:click.prevent="toggleNote">
                            <textarea name="" id="" placeholder="Click here to add new notes"
                                disabled>{{ $currentTask->note }}</textarea>
                        </div>
                    @endif
                    @if ($isTonggleNote)
                        <div class="mt-4">
                            <textarea wire:model="note" name="note" id="edit-note" placeholder="Add notes here..."
                                autofocus></textarea>
                            <br>
                            <button wire:click="saveNotes({{ $currentTask->id }})"
                                class="btn btn-primary">Save</button>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

</div>
