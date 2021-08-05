@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="fade-in">
            @livewire('checklist-show', ['checklist' => $checklist])
        </div>
    </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function() {
            // $('.task-description-toggle').click(function() {
            //     console.log($(this).data('id'));
            //     $('#task-caret-up-' + $(this).data('id')).toggleClass('d-none');
            //     $('#task-caret-down-' + $(this).data('id')).toggleClass('d-none');
            //     $('#task-description-' + $(this).data('id')).toggleClass('d-none');
            // })
        })

    </script>
@endsection
