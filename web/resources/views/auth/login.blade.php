@extends('layouts.app')

@section('pageCss')
    <link href="{{ url('/') }}/css/main.css" rel="stylesheet">
@endsection

@section('content')
    <div id="banner">
        <div id="banner-wrapper" class="clearfix">
            <div class="banner-slogan">
                <p>Atypisch Antwerpen</p>

            </div>
            <div class="banner-text">
                <p>Projecten</p>
                <p>in jouw</p>
                <p>buurt</p>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12 login">
                <h1>Aanmelden</h1>
                <h3>Schrijf je nu in!</h3>
                <form class="form-horizontal" role="form" method="POST" action="{{ url('/inloggen') }}">
                    {!! csrf_field() !!}

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">E-Mail Address</label>

                        <div class="col-md-5">
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

                        <div class="col-md-5">
                            <input type="password" class="form-control" name="password">

                            @if ($errors->has('password'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-5 col-md-offset-4">
                            <a class="btn btn-link" href="{{ url('/wachtwoord/reset') }}">Forgot Your Password?</a>
                            <div class="checkbox pull-right">
                                <label>
                                    <input type="checkbox" name="remember"> Remember Me
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-9 col-md-offset-0">
                            <button type="submit" class="btn btn-primary loginButton pull-right">
                                <i class="fa fa-btn fa-sign-in"></i>Login
                            </button>
                        </div>
                    </div>
                </form>
                <div id="aProfiel">
                    <a class="btn btn-link" href="{{ $OAuthLink }}">Meld aan met A-profiel</a>
                </div>
            </div>
        </div>
    </div>
@endsection
