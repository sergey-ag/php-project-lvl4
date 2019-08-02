@extends('layouts.app')

@section('content')
<div class="container">
    @include('partials.flash')
    
    <h1>{{ __('Tasks') }}</h1>
    <div class="btn-group my-3" role="group" aria-label="Control buttons">
        <a href="{{ route('tasks.create') }}" class="btn btn-outline-secondary" role="button" aria-pressed="true">{{ __('Create task') }}</a>
    </div>
    <div class="row">
        <div class="col-md-2">
            <h4>{{ __('Filters') }}</h4>
            <form method="GET" action="{{ route('tasks.index') }}">
                <h5>{{ __('Statuses') }}</h5>
                @foreach ($statuses as $status)

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="{{ $status->id }}" id="statusFilter{{ $status->id }}" name="statusFilter[]"{{ (in_array($status->id, $statusFilter) ? ' checked="checked"' : '') }}>
                    <label class="form-check-label" for="statusFilter{{ $status->id }}">{{ $status->name }}</label>
                </div>

                @endforeach
                <h5 class="mt-3">{{ __('Users') }}</h5>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="null" id="userFilter0" name="userFilter[]"{{ (in_array('null', $userFilter) ? ' checked="checked"' : '') }}>
                    <label class="form-check-label" for="userFilter0">{{ __('Not assigned') }}</label>
                </div>
                @foreach ($users as $user)

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="{{ $user->id }}" id="userFilter{{ $user->id }}" name="userFilter[]"{{ (in_array($user->id, $userFilter) ? ' checked="checked"' : '') }}>
                    <label class="form-check-label" for="userFilter{{ $user->id }}">{{ $user->name }}</label>
                </div>

                @endforeach
                <h5 class="mt-3">{{ __('Tags') }}</h5>
                @foreach ($tags as $tag)

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="{{ $tag->id }}" id="tagFilter{{ $tag->id }}" name="tagFilter[]"{{ (in_array($tag->id, $tagFilter) ? ' checked="checked"' : '') }}>
                    <label class="form-check-label" for="tagFilter{{ $user->id }}">{{ $tag->name }}</label>
                </div>

                @endforeach
                <div class="form-group row my-2">
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Filter') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-10">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th scope="col">{{ __('Creator') }}</th>
                        <th scope="col">{{ __('Assigned To') }}</th>
                        <th scope="col">{{ __('Created at') }}</th>
                        <th scope="col">{{ __('Updated at') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tasks as $task)
                    <tr>
                        <td><a href="{{ route('tasks.show', ['id' => $task->id]) }}">{{ $task->name }}</a></td>
                        <td>{{ $task->status->name }}</td>
                        <td>{{ $task->creator->name }}</td>
                        <td>{{ $task->assignedTo ? $task->assignedTo->name : __('Not assigned') }}</td>
                        <td>{{ $task->created_at }}</td>
                        <td>{{ $task->updated_at }}</td>
                    </tr>
                    @empty
                    <p>{{ __('No records') }}</p>
                    @endforelse
                </tbody>
            </table>
            {{ $tasks->appends(request()->except('page'))->links() }}
        </div>
    </div>
</div>
@endsection
