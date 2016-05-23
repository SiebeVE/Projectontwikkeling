@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-md-12">
            <h1>Admin</h1>
            @if($user->isAdmin())
                <h2>Bent u zeker dat u deze gebruiker administrator rechten wil ontnemen?</h2>
            @else
                <h2>Bent u zeker dat u deze gebruiker administrator rechten wil geven?</h2>
            @endif
            <form method="post">
                <div class="alert alert-danger">
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
                <div class="col-sm-1"><b>Voornaam</b></div>
                <div class="col-sm-11">{{ $user->firstname }}</div>
                <div class="col-sm-1"><b>Achternaam</b></div>
                <div class="col-sm-11">{{ $user->lastname }}</div>
                <div class="col-sm-1"><b>E-mail</b></div>
                <div class="col-sm-11">{{ $user->email }}</div>
                {{csrf_field()}}
                <div class="col-sm-12 admin-controls">
                    <button type="submit" class="btn-success btn" href>Ja, {{ $user->isAdmin()? "ontneem de rechten" : "geef de rechten"}}</button>
                    <a class="text-danger" href="{{ url('/admin/paneel') }}">Annuleer</a>
                </div>
            </form>
        </div>
    </div>
@endsection