@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="fade-in">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            {{ $checklist->name }}
                        </div>

                        <div class="card-body">
                            <table class="table table-responsive-sm">
                                <thead>
                                    @foreach ($checklist->tasks as $task)
                                        <tr>
                                            <td></td>
                                            <td class="task-description-toggle" data-id="{{ $task->id }}">
                                                {{ $task->name }}</td>
                                            <td>
                                                <svg id="task-caret-up-{{ $task->id }}"
                                                    class="c-sidebar-nav-icon d-none">
                                                    <use
                                                        xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-chevron-circle-up-alt') }}">
                                                    </use>
                                                </svg>
                                                <svg id="task-caret-down-{{ $task->id }}" class="c-sidebar-nav-icon">
                                                    <use
                                                        xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-chevron-circle-down-alt') }}">
                                                    </use>
                                                </svg>
                                            </td>
                                        </tr>
                                        <tr class="d-none" id="task-description-{{ $task->id }}">
                                            <td></td>
                                            <td colspan="2">{!! $task->description !!}</td>
                                        </tr>
                                    @endforeach

                                </thead>

                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function() {
            $('.task-description-toggle').click(function() {
                console.log($(this).data('id'));
                $('#task-caret-up-' + $(this).data('id')).toggleClass('d-none');
                $('#task-caret-down-' + $(this).data('id')).toggleClass('d-none');
                $('#task-description-' + $(this).data('id')).toggleClass('d-none');
            })
        })

    </script>
@endsection
