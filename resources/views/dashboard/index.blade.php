@extends('layouts.app')

@section('content')

    @if (Auth::user()->mailchimp and Auth::user()->token)

        @if($count == 0)
            <p>You have no unsynced contacts.</p>
        @else
            <p>You have {{ $count }} unsynced contacts.</p>
            <p>To sync your contacts <button  href="{{ url('/contacts') }}" class="btn btn-default">click here</button></p>
        @endif
    @endif

@endsection
