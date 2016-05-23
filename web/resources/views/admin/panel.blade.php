@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-md-12">
            <h1>Admin</h1>
            <table class="table" id="users-table">
                <thead>
                <tr>
                    <th>Achternaam</th>
                    <th>Voornaam</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->lastname }}</td>
                        <td>{{ $user->firstname }}</td>
                        <td>
                            @if(Auth::user() == $user)
                                <span class="text-warning">U kan uw eigen rechten niet aanpassen.</span>
                            @else
                                <a href='{{url('/admin/paneel/rechten',$user->id)}}'>{{ $user->is_admin != 1 ? "Geef administrator rechten" : "Verwijder administrator rechten" }}</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('pageJs')
    <script src="{{ url('/') }}/js/jquery.filtertable.min.js"></script>
    <script>
        jQuery.noConflict();
        (function ( $ ) {
            $(function () {
                var stripeTable = function ( $table ) { //stripe the table (jQuery selector)
                    $table.find('tr').removeClass('striped').filter(':visible:even').addClass('striped');
                };

                var $table = $("table#users-table");
                $table.filterTable({
                    minRows: 3,
                    placeholder: "Zoek in deze tabel",
                    callback: function ( term, table ) {
                        stripeTable(table);
                    }
                });

                stripeTable($table);
            });
        })(jQuery);
    </script>
@endsection