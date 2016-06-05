@extends('layouts.app')

@section('pageCss')
    <link href="{{ url('/') }}/css/main.css" rel="stylesheet">
    <link href="{{ url('/') }}/css/cover.css" rel="stylesheet">
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

    <div class="container dashboard">
        <div class="col-md-12 projectoverzicht">
            <h1>Projecten van Stad Antwerpen</h1>
            <div id="myCarousel" class="carousel slide" data-ride="carousel">

                <!-- Indicators -->
                <ol class="carousel-indicators">
                    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                    @foreach($projects as $project)
                        @foreach($project->phases as $i => $phase)
                            <?php $ended = false ?>
                            @if($phase->end <= $mytime)
                                <?php $ended = true ?>
                            @endif
                        @endforeach
                        @if($ended == false)
                            <li data-target="#myCarousel" data-slide-to="{{$project->id}}"></li>
                        @endif
                    @endforeach
                </ol>

                <!-- Wrapper for slides -->
                <div class="carousel-inner" role="listbox">

                    <div class="item active carouselholder">
                        <div class="imagePlaceholder">
                            <img class="projectImageSlide" src="{{ url('/images/Grote_Markt.jpg') }}"
                                 alt="project foto"/>
                            <div class="carousel-caption">
                                <h3>Bekijk en beoordeel de projecten</h3>
                                <p>Elke fase van een project heeft jou mening nodig!</p>
                            </div>
                        </div>
                    </div>

                    @foreach($projects as $project)
                        @foreach($project->phases as $i => $phase)
                            <?php $ended = false ?>
                            @if($phase->end <= $mytime)
                                <?php $ended = true ?>
                            @endif
                        @endforeach
                        @if($ended == false)
                            <div class="item carouselholder">
                                <div class="imagePlaceholder">
                                    <img class="projectImageSlide" src="{{$project->photo_path}}" alt="project foto"/>
                                    <div class="carousel-caption">
                                        <h3>{{$project->name}}</h3>
                                        <p>{{$project->description}}</p>
                                        <div class="hoverprojectlink">
                                            <a href="{{ url('/project/beoordelen',$project->id) }}">Bekijk het
                                                project</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
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
                @foreach($project->phases as $i => $phase)
                    <?php $ended = false ?>
                    @if($phase->end <= $mytime)
                        <?php $ended = true ?>
                    @endif
                @endforeach
                @if($ended == false)
                    <div class="projectbekijken col-md-12">

                        <div class="col-md-8 projectbekijkeninfo">
                            <h3><a href="{{ url('/project/beoordelen',$project->id) }}">{{$project->name}}</a></h3>
                            <p>{{$project->description}}</p>
                            <h5>Adres: {{$project->address}}</h5>
                            <div class="datums col-md-12">
                                @foreach($project->phases as $i => $phase)
                                    @if ($i == 0)
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
                            <div id="imagePlaceholder">
                                <img class="projectImage" style="left: {{$project->photo_left_offset}};"
                                     src="{{$project->photo_path}}" alt="project foto"/>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="projectbekijken col-md-12">

                        <div class="col-md-8 projectbekijkeninfo">
                            <h3><a href="{{ url('project/statistieken',$project->id) }}">{{$project->name}}</a><span
                                        class="afgesloten"> (Afgesloten)</span></h3>
                            <p>{{$project->description}}</p>
                            <h5>Adres: {{$project->address}}</h5>
                            <div class="datums col-md-12">
                                @foreach($project->phases as $i => $phase)
                                    @if ($i == 0)
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
                            <div id="imagePlaceholder">
                                <img class="projectImage" style="left: {{$project->photo_left_offset}};"
                                     src="{{$project->photo_path}}" alt="project foto"/>
                            </div>
                        </div>
                    </div>
                @endif
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