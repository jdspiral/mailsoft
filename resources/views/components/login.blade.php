@extends('layouts.master')

@section('content')

        {{--<div class="large-6 columns">--}}
            {{--<div class="signup-panel">--}}
                {{--<p class="welcome"> Welcome to this awesome app!</p>--}}
                {{--<form role="form" method="POST" action="{{ url('/register') }}">--}}
                    {{--{!! csrf_field() !!}--}}
                    {{--<div class="row collapse{{ $errors->has('name') ? ' has-error' : '' }}">--}}
                        {{--<div class="small-2  columns">--}}
                            {{--<span class="prefix"><i class="fi-torso-female"></i></span>--}}
                        {{--</div>--}}
                        {{--<div class="small-10  columns">--}}
                            {{--<input type="text" value="{{ old('name') }}">--}}
                            {{--@if ($errors->has('name'))--}}
                                {{--<span class="help-block">--}}
                                    {{--<strong>{{ $errors->first('name') }}</strong>--}}
                                {{--</span>--}}
                            {{--@endif--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="row collapse{{ $errors->has('email') ? ' has-error' : '' }}">--}}
                        {{--<div class="small-2 columns">--}}
                            {{--<span class="prefix"><i class="fi-mail"></i></span>--}}
                        {{--</div>--}}
                        {{--<div class="small-10  columns">--}}
                            {{--<input type="email" value="{{ old('email') }}">--}}
                            {{--@if ($errors->has('email'))--}}
                                {{--<span class="help-block">--}}
                                    {{--<strong>{{ $errors->first('email') }}</strong>--}}
                                {{--</span>--}}
                            {{--@endif--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="row collapse{{ $errors->has('password') ? ' has-error' : '' }}">--}}
                        {{--<div class="small-2 columns ">--}}
                            {{--<span class="prefix"><i class="fi-lock"></i></span>--}}
                        {{--</div>--}}
                        {{--<div class="small-10 columns ">--}}
                            {{--<input type="password" name="password">--}}
                            {{--@if ($errors->has('password'))--}}
                                {{--<span class="help-block">--}}
                                    {{--<strong>{{ $errors->first('password') }}</strong>--}}
                                {{--</span>--}}
                            {{--@endif--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="row collapse{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">--}}
                        {{--<div class="small-2 columns ">--}}
                            {{--<span class="prefix"><i class="fi-lock"></i></span>--}}
                        {{--</div>--}}
                        {{--<div class="small-10 columns ">--}}
                            {{--<input type="password" name="password_confirmation">--}}
                            {{--@if ($errors->has('password_confirmation'))--}}
                                {{--<span class="help-block">--}}
                                    {{--<strong>{{ $errors->first('password_confirmation') }}</strong>--}}
                                {{--</span>--}}
                            {{--@endif--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</form>--}}
                {{--<a href="#" class="button ">Sign Up! </a>--}}
                {{--<p>Already have an account? <a href="#">Login here &raquo</a></p>--}}
            {{--</div>--}}
        {{--</div>--}}
@endsection

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Register</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}">

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Confirm Password</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password_confirmation">

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-user"></i>Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>