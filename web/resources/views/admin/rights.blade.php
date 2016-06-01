@extends('layouts.app')

@section('pageCss')
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
        <div class="col-md-12 admin admin-panel">
            <h1>Admin</h1>
            @if($user->isAdmin())
                <h2>Bent u zeker dat u deze gebruiker administrator rechten wil ontnemen?</h2>
            @else
                <h2>Bent u zeker dat u deze gebruiker administrator rechten wil geven?</h2>
            @endif
            <form method="post">
                <div class="danger-message text-danger">
                    @if($user->isAdmin())
                        <p>U staat op het punt om een gebruiker administrator rechten te ontnemen. Deze gebruiker zal
                            dan geen rechten meer hebben om projecten te maken,bewerken,...</p>
                        <p>Deze gebruiker zal dan ook geen rechten meer hebben om andere rechten te geven en ontnemen.</p>
                    @else
                        <p>U staat op het punt om een gebruiker administrator te maken. Deze gebruiker zal dan alle
                            rechten
                            hebben om projecten te maken,bewerken,...</p>
                        <p>Deze gebruiker zal dan ook de rechten hebben om andere rechten te geven en ontnemen.</p>
                    @endif
                </div>
                <div class="col-sm-6 admin-list"><b>Voornaam:</b></div>
                <div class="col-sm-6 admin-info">{{ $user->firstname }}</div>
                <div class="col-sm-6 admin-list"><b>Achternaam:</b></div>
                <div class="col-sm-6 admin-info">{{ $user->lastname }}</div>
                <div class="col-sm-6 admin-list"><b>E-mail:</b></div>
                <div class="col-sm-6 admin-info">{{ $user->email }}</div>
                {{csrf_field()}}
                <div class="col-sm-10 admin-controls col-lg-offset-1">
                    <a class="text-danger pull-left" href="{{ url('/admin/paneel') }}">Annuleer</a>
                    <button type="submit" class="btn-primary btn pull-right" href>Ja, {{ $user->isAdmin()? "ontneem de rechten" : "geef de rechten"}}</button>
                </div>
            </form>
        </div>
    </div>
@endsection