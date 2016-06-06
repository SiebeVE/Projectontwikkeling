@extends('layouts.app')

@section('pageCss')
	<link rel="stylesheet" href="//cdn.jsdelivr.net/bootstrap.tagsinput/0.4.2/bootstrap-tagsinput.css"/>
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
			<h1>Standaard vraag aanpassen</h1>
			@if(count($errors) > 0)
				<div class="alert alert-danger">
					<ul>
						@foreach($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif
			<form name="create" method="POST">
				{!! csrf_field() !!}
				<input type="hidden" name="numberOfFields" id="numberOfFields" value="0">
				<div id="defaultQuestions" class="hidden">
					<div class="default-question" data-id="{{$question->id}}">
							<span data-sort="{{$question->sort}}"
								  data-width="{{$question->width}}">{{$question->question}}</span>
						@if(count($question->possibleAnswers) > 0)
							<div class="possibleAnswers">
								@foreach($question->possibleAnswers as $answer)
									<span data-id="{{$answer->id}}">{{$answer->answer}}</span>
								@endforeach
							</div>
						@endif
					</div>
				</div>
				<div id="example-field" class="grid">
					<div class="gutter-sizer"></div>
					<div class="grid-size"></div>
				</div>
				<div class="col-md-12 control-field">
					<div class="sort-control">
						<div class="form-group">
							<b class="form-label">Wat wil je toevoegen?</b>
							<div class="active controlsType" data-sort="question"><span><i class="fa fa-comment-o"
																						   aria-hidden="true"></i> Vragen</span>
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
				</div>
				<div>
					<button type="submit" class="btn btn-succes createButton pull-right">Vraag aanpassen</button>
					<a id="cancel" href="{{url('/admin/project/dashboard')}}" class="text-danger pull-right">Annuleer</a>
				</div>
			</form>
		</div>
	</div>
@endsection

@section('pageJs')
	<script>

	</script>
	<script src="https://cdn.jsdelivr.net/sweetalert2/3.2.3/sweetalert2.min.js"></script>
	<script src="{{ url('/js/jquery.form.min.js')}}"></script>
	<script src="{{ url('/js/fitVid.js')}}"></script>
	<script src="{{ url('/js/draggabilly.pkgd.js') }}"></script>
	<script src="{{ url('/js/packery.pkgd.min.js') }}"></script>
	<script src="{{ url('/js/nouislider.min.js') }}"></script>
	<script src="{{ url('/js/layoutDefaultQuestion.js') }}"></script>

@endsection