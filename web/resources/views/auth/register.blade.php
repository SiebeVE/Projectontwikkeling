@extends('layouts.app')


@section('pageCss')
    <link rel="stylesheet" href="//cdn.jsdelivr.net/bootstrap.tagsinput/0.4.2/bootstrap-tagsinput.css"/>
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
            <div class="col-md-12 register">
                <h1>Registreer</h1>
                <h3>Schrijf je nu in!</h3>
                <form class="form-horizontal" role="form" method="POST" action="{{ url('/registreer') }}">
                    {!! csrf_field() !!}

                    <div class="form-group{{ $errors->has('firstname') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label" for="firstname">Voornaam:</label>

                        <div class="col-md-5">
                            <input type="text" class="form-control" id="firstname" name="firstname"
                                   value="{{ old('firstname') }}">

                            @if ($errors->has('firstname'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('firstname') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('lastname') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label" for="lastname">Achternaam:</label>

                        <div class="col-md-5">
                            <input type="text" class="form-control" id="lastname" name="lastname"
                                   value="{{ old('lastname') }}">

                            @if ($errors->has('lastname'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('lastname') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('postal_code') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label" for="postal_code">Postcode:</label>

                        <div class="col-md-5">
                            <input type="number" class="form-control" id="postal_code" name="postal_code"
                                   value="{{ old("postal_code") }}">

                            @if ($errors->has('postal_code'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('postal_code') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label" for="city">Gemeente:</label>

                        <div class="col-md-5">
                            <input type="text" class="form-control" id="city" name="city" value="{{ old("city") }}">

                            @if ($errors->has('city'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('city') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label" for="email">E-mailadres:</label>

                        <div class="col-md-5">
                            <input type="email" class="form-control" id="email" name="email"
                                   value="{{ old('email') }}">

                            @if ($errors->has('email'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label" for="password">Wachtwoord:</label>

                        <div class="col-md-5">
                            <input type="password" class="form-control" id="password" name="password">

                            @if ($errors->has('password'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label" for="password_confirmation">Bevestig
                            wachtwoord:</label>

                        <div class="col-md-5">
                            <input type="password" class="form-control" id="password_confirmation"
                                   name="password_confirmation">

                            @if ($errors->has('password_confirmation'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-9 col-md-offset-0">
                            <button type="submit" class="btn btn-primary pull-right registerButton">
                                <i class="fa fa-btn fa-user"></i>Registeer
                            </button>
                        </div>
                    </div>
                </form>
                <div id="aProfiel">
                    <a href="{{ env("REGISTER_LINK_A_PROFILE") }}" target="_blank">Aanmelden met A-profiel</a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <footer class="footer">
        <div class="container text-center">
            <p class="text-muted">&copy; 2016 Stad Antwerpen</p>
        </div>
    </footer>
@endsection


@section('pageJs')
@endsection
