@extends('layouts.app')

@section('pageCss')
	<link rel="stylesheet" href="//cdn.jsdelivr.net/bootstrap.tagsinput/0.4.2/bootstrap-tagsinput.css"/>
	<link href="{{ url('/') }}/css/main.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/sweetalert2/3.2.3/sweetalert2.min.css">
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

	<div class="container containerPhase">
		<div class="col-md-12">
			<h1>{{ $phase->name }} maken</h1>
			<p class="text-muted">Van {{ date("d/m/Y", strtotime($phase->start)) }}
				tot {{ date("d/m/Y", strtotime($phase->end)) }}</p>
			@if(count($errors) > 0)
				<div class="alert alert-danger">
					<ul>
						@foreach($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif
			<form name="createPhase" method="POST">
				{!! csrf_field() !!}
				<input type="hidden" name="numberOfFields" id="numberOfFields" value="0">
				<div id="example-field" class="grid">
					<div class="gutter-sizer"></div>
					<div class="grid-size"></div>
				</div>
				<div class="col-md-12 control-field">
					<div class="sort-control">
						<div class="form-group">
							<b class="form-label">Wat wil je toevoegen?</b>
							<div class="active" data-sort="question"><span><i class="fa fa-comment-o"
																			  aria-hidden="true"></i> Vragen</span>
							</div>
							<div data-sort="media"><span><i class="fa fa-picture-o" aria-hidden="true"></i> Media</span>
							</div>
						</div>
					</div>
					<div id="question-control">
						<div class="form-group">
							<label for="sortQuestion">Kies je soort vraag</label>
							<select id="sortQuestion" name="sortQuestion">
								<option value="choose" selected disabled>Maak een keuze</option>
								<option value="text">Open vraag 1 regel</option>
								<option value="textarea">Open vraag meerdere regels</option>
								<option value="checkbox">Meerkeuze vraag</option>
								<option value="radio">Enkele keuze</option>
							</select>
						</div>
						<div class="form-group">
							<label for="question" class="control-label">Vraag</label>
							<input type="text" id="question" name="question" class="form-control">
						</div>
						<div class="form-group">
							<label for="blockWidth">Breedte van blok</label>
							<div id="slider"></div>
						</div>
						<div id="input-specific"></div>
						<a id="addBlock" class="btn btn-success">Blok toevoegen</a>
						<a id="cancel-block" class="text-danger">Annuleer blok aanpassen</a>
					</div>
					<div id="media-control">
						<div class="form-group">
							<label for="sortMedia">Kies je soort media</label>
							<select id="sortMedia" name="sortMedia">
								<option value="choose" selected disabled>Maak een keuze</option>
								<option value="picture">Foto</option>
								<option value="youtube">Video - Youtube</option>
							</select>
						</div>
						<div class="form-group">
						</div>
						<div class="form-group">
							<label for="blockWidthMedia">Breedte van blok</label>
							<div id="slider-media"></div>
						</div>
						<div id="input-specific-media"></div>
						<a id="addBlockMedia" class="btn btn-success">Blok toevoegen</a>
						<a id="cancel-block-media" class="text-danger">Annuleer blok aanpassen</a>
					</div>
				</div>
				<button type="submit" class="btn btn-succes createButton pull-right">Fase toevoegen</button>
			</form>
		</div>
	</div>
@endsection

@section('pageJs')
	<script src="https://cdn.jsdelivr.net/sweetalert2/3.2.3/sweetalert2.min.js"></script>
	<script src="{{ url('/js/draggabilly.pkgd.js') }}"></script>
	<script src="{{ url('/js/packery.pkgd.min.js') }}"></script>
	<script src="{{ url('/js/nouislider.min.js') }}"></script>
	<script src="{{ url('/js/dragLayout.js') }}"></script>
	<script>
		(function ( $ ) {
			function checkYoutubeUrl( url ) {
				var regExp = /^.*(youtu\.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
				var match = url.match(regExp);
				if (match && match[ 2 ].length == 11) {
					return match[ 2 ];
				}
				else {
					return null
				}
			}
		})(jQuery);
	</script>

@endsection