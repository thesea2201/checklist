@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="fade-in">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        @if ($errors->storeupdatePagePage->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->updatePage->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (session('message'))
                        <div class="alert alert-info">
                            {{ session('message') }}
                        </div>
                        @endif
                        <form action="{{ route('admin.pages.update', [$page]) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="card-header">{{ __('Edit page') }}</div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="name">{{ __('Title') }}</label>
                                            <input class="form-control" value="{{ $page->title }}" name="title"
                                                type="text" placeholder="Enter page title">
                                        </div>
                                        <div class="form-group">
                                            <label for="description">{{ __('Page content') }}</label>
                                            <textarea id="page-editor" class="form-control" name="content"
                                                rows="5">{{ $page->content }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-sm btn-primary" type="submit">{{ __('Save page') }}</button>
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
            .create(document.querySelector('#page-editor'))
            .catch(error => {
                console.error(error);
            });
    </script>

@endsection
