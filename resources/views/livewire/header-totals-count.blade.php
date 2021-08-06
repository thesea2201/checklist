<div class="row">
    <div class="col-md-9">
        <div class="card">
            <div class="card-header">
                <h3>{{ __('Store review') }}</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-9">
                        <div class="row">
                            @foreach ($checklists as $checklist)
                                <div class="col-md-3">
                                    <div class="progress-group mb-4">
                                        <div class="progress-group-bars">
                                            <strong>{{ $checklist->user_tasks_count }}/{{ $checklist->tasks_count }}</strong>
                                            <div class="progress progress-xs">
                                                @if ($checklist->tasks_count)
                                                    <div class="progress-bar @if($checklist->user_tasks_count / $checklist->tasks_count ==1) bg-success @else bg-info @endif" role="progressbar"
                                                        style="width: {{ 100 * $checklist->user_tasks_count / $checklist->tasks_count }}%"
                                                        aria-valuenow="{{ $checklist->user_tasks_count / $checklist->tasks_count }}"
                                                        aria-valuemin="0" aria-valuemax="100"></div>
                                                @endif
                                            </div>
                                            <strong>{{ $checklist->name }}</strong>
                                        </div>
                                    </div>
                                </div>

                            @endforeach

                        </div>
                    </div>

                    <div class="col-md-3">
                        <h2>{{ $checklists->sum('user_tasks_count') }}/{{ $checklists->sum('tasks_count') }}</h2>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
