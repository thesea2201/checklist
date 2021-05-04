@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="fade-in">
            <div class="row justify-content-center">
                <div class="col-md-12">
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
                        <form action="{{ route('admin.checklists.tasks.update', [$checklist, $task]) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="card-header">{{ __('Edit task') }}</div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="name">{{ __('Name') }}</label>
                                            <input class="form-control" value="{{ $task->name }}" name="name"
                                                type="text" placeholder="Enter task name">
                                        </div>
                                        <div class="form-group">
                                            <label for="description">{{ __('Task description') }}</label>
                                            <textarea id="task-editor" class="form-control" name="description"
                                                rows="5">{{ $task->description }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-sm btn-primary" type="submit">{{ __('Save Task') }}</button>
                            </div>
                        </form>

                        {{-- <form
                            action="{{ route('admin.checklist_groups.checklists.destroy', [$checklistGroup, $checklist]) }}"
                            method="POST">
                            @csrf
                            @method('DELETE')

                            <div class="card-footer">
                                <button class="btn btn-sm btn-danger" type="submit"
                                    onclick="return confirm('{{ __('Are you sure?') }}')">{{ __('Delete this checklist') }}</button>
                            </div>
                        </form> --}}
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
    </script>

@endsection
