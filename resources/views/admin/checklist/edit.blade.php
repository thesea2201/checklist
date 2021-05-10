@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="fade-in">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div id="trigger" class="card-header pointer" data-trigger="edit-fader">{{ __('Edit checklist') }}</div>
                    <div id="edit-fader" class="card fade-out">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form
                            action="{{ route('admin.checklist_groups.checklists.update', [$checklistGroup, $checklist]) }}"
                            method="POST">
                            @csrf
                            @method('PUT')


                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="name">{{ __('Name') }}</label>
                                            <input class="form-control" value="{{ $checklist->name }}" name="name"
                                                type="text" placeholder="Enter checklist name">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-sm btn-primary" type="submit">{{ __('Save Checklist') }}</button>
                            </div>
                        </form>

                        <form
                            action="{{ route('admin.checklist_groups.checklists.destroy', [$checklistGroup, $checklist]) }}"
                            method="POST">
                            @csrf
                            @method('DELETE')

                            <div class="card-footer">
                                <button class="btn btn-sm btn-danger" type="submit"
                                    onclick="return confirm('{{ __('Are you sure?') }}')">{{ __('Delete this checklist') }}</button>
                            </div>
                        </form>
                    </div>

                    <hr>

                    <div class="card">
                        <div class="card-header"><i class="fa fa-align-justify"></i> {{ __('Task list') }}</div>
                        <div class="card-body">
                            <table class="table table-responsive-sm">
                                <thead>
                                    <tr>
                                        <th>{{ __('Task name') }}</th>
                                        <th>{{ __('Description') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($checklist->tasks as $task)
                                        <tr>
                                            <td>{{ $task->name }}</td>
                                            <td style="overflow: hidden;
                                            text-overflow: ellipsis;
                                            display: -webkit-box;
                                            -webkit-line-clamp: 2; /* number of lines to show */
                                            -webkit-box-orient: vertical;
                                            max-height: 3.8em;">{!! $task->description !!}</td>
                                            <td>
                                                <a class="btn btn-sm btn-primary"
                                                    href="{{ route('admin.checklists.tasks.edit', [$checklist, $task]) }}">{{ __('Edit') }}</a>
                                                <form style="display: inline-block"
                                                    action="{{ route('admin.checklists.tasks.destroy', [$checklist, $task]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button class="btn btn-sm btn-danger" type="submit"
                                                        onclick="return confirm('{{ __('Are you sure?') }}')">{{ __('Delete this task') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card">
                        @if ($errors->storeTask->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->storeTask->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('admin.checklists.tasks.store', [$checklist]) }}" method="POST">
                            @csrf

                            <div class="card-header">{{ __('New Task') }}</div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="name">{{ __('Task name') }}</label>
                                            <input value="{{ old('name') }}" class="form-control" name="name" type="text"
                                                placeholder="Enter task name">
                                        </div>
                                        <div class="form-group">
                                            <label for="description">{{ __('Task description') }}</label>
                                            <textarea id="task-editor" class="form-control" name="description"
                                                rows="5">{{ old('description') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-sm btn-primary" type="submit">{{ __('Save Task') }}</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
    </div>
@endsection

@section('scripts')
    <script>
        ClassicEditor
            .create(document.querySelector('#task-editor'))
            .catch(error => {
                console.error(error);
            });

            $("#trigger").click(function () {
                let _this = $(this);
                let fader = _this.data('trigger');
                if(fader == '') return;
                fader = $(`#${fader}`);
                if (fader.hasClass("fade-out")) {
                    fader.removeClass("fade-out").addClass("fade-in");
                } else {
                    fader.removeClass("fade-in").addClass("fade-out");
                }
            });
    </script>

@endsection
