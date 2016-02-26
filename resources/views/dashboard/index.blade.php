@extends('layouts.app')

@section('content')

    @if (Auth::user()->mailchimp and Auth::user()->token)

        @if($count == 0)
            <p>You have no unsynced contacts.</p>
        @else
            <p>You have {{ $count }} contacts in Mailchimp that can be added to Infusionsoft.</p>
            <p>To import your contacts <button  href="{{ url('/contacts') }}" class="btn btn-default">click here</button></p>
            <p>If you would like to tag your contacts before import, please select which tags you would like to add.</p>
            @include('partials.tags')
        @endif
    @endif

@endsection
