@extends('layouts.app')

@section('pageCss')
    <link href="{{ url('/') }}/css/main.css" rel="stylesheet">
@endsection

@section('content')
    <div id="banner" >
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

    <div class="container dashboard">
        <div class="col-md-12 projectoverzicht">
            <h1>Overzicht</h1>
            <h3>Bekijk en beoordeel jouw favoriete projecten</h3>

            <div id="myCarousel" class="carousel slide" data-ride="carousel">

                <!-- Indicators -->
                <ol class="carousel-indicators">
                    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                    @foreach($projects as $project)
                        <li data-target="#myCarousel" data-slide-to="{{$project->id}}" ></li>
                    @endforeach
                </ol>

                <!-- Wrapper for slides -->
                <div class="carousel-inner" role="listbox">

                    <div  class="item active">
                        <img class="projectImage" src="{{ url('/images/Grote_Markt.jpg') }}" alt="project foto"/>
                    </div>

                    @foreach($projects as $project)
                        <div  class="item">
                            <img class="projectImage" src="{{$project->photo_path}}" alt="project foto"/>
                        </div>
                    @endforeach
                </div>


                <!-- Left and right controls -->
                <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>

                    @foreach($projects as $project)
                        <div class="projectbekijken col-md-12">
                             <div class="col-md-8 projectbekijkeninfo">
                                <h3><a href="#">{{$project->name}}</a></h3>
                                <p>{{$project->description}}</p>
                                <h5>Adres: {{$project->address}}</h5>
                                <div class="datums col-md-12">
                                        @foreach($project->phases as $i => $phase)
                                            @if ($i == count($project->phases) - 1)
                                               <span>Begint op {{ date("d/m/Y", strtotime($phase->start)) }}</span>
                                            @endif
                                        @endforeach
                                        @foreach($project->phases as $i => $phase)
                                            @if ($i == count($project->phases) - 1)
                                                <span class="pull-right"> Eindigt op {{ date("d/m/Y", strtotime($phase->end)) }}</span>
                                            @endif
                                        @endforeach
                                </div>
                            </div>
                            <div class="col-md-4">
                                <img class="projectImage" src="{{$project->photo_path}}" alt="project foto"/>
                            </div>
                        </div>
                    @endforeach

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