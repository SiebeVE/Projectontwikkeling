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
				<input type="hidden" class="hidden" name="token" id="token" value="{{ $token }}">
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
							<div data-sort="media" class="controlsType"><span><i class="fa fa-picture-o" aria-hidden="true"></i> Media</span>
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
	<script>
		/*jshint browser:true */
		/*!
		 * FitVids 1.1
		 *
		 * Copyright 2013, Chris Coyier - http://css-tricks.com + Dave Rupert - http://daverupert.com
		 * Credit to Thierry Koblentz - http://www.alistapart.com/articles/creating-intrinsic-ratios-for-video/
		 * Released under the WTFPL license - http://sam.zoy.org/wtfpl/
		 *
		 */

		;(function( $ ){

			'use strict';

			$.fn.fitVids = function( options ) {
				var settings = {
					customSelector: null,
					ignore: null
				};

				if(!document.getElementById('fit-vids-style')) {
					// appendStyles: https://github.com/toddmotto/fluidvids/blob/master/dist/fluidvids.js
					var head = document.head || document.getElementsByTagName('head')[0];
					var css = '.fluid-width-video-wrapper{width:100%;position:relative;padding:0;}.fluid-width-video-wrapper iframe,.fluid-width-video-wrapper object,.fluid-width-video-wrapper embed {position:absolute;top:0;left:0;width:100%;height:100%;}';
					var div = document.createElement("div");
					div.innerHTML = '<p>x</p><style id="fit-vids-style">' + css + '</style>';
					head.appendChild(div.childNodes[1]);
				}

				if ( options ) {
					$.extend( settings, options );
				}

				return this.each(function(){
					var selectors = [
						'iframe[src*="player.vimeo.com"]',
						'iframe[src*="youtube.com"]',
						'iframe[src*="youtube-nocookie.com"]',
						'iframe[src*="kickstarter.com"][src*="video.html"]',
						'object',
						'embed'
					];

					if (settings.customSelector) {
						selectors.push(settings.customSelector);
					}

					var ignoreList = '.fitvidsignore';

					if(settings.ignore) {
						ignoreList = ignoreList + ', ' + settings.ignore;
					}

					var $allVideos = $(this).find(selectors.join(','));
					$allVideos = $allVideos.not('object object'); // SwfObj conflict patch
					$allVideos = $allVideos.not(ignoreList); // Disable FitVids on this video.

					$allVideos.each(function(){
						var $this = $(this);
						if($this.parents(ignoreList).length > 0) {
							return; // Disable FitVids on this video.
						}
						if (this.tagName.toLowerCase() === 'embed' && $this.parent('object').length || $this.parent('.fluid-width-video-wrapper').length) { return; }
						if ((!$this.css('height') && !$this.css('width')) && (isNaN($this.attr('height')) || isNaN($this.attr('width'))))
						{
							$this.attr('height', 9);
							$this.attr('width', 16);
						}
						var height = ( this.tagName.toLowerCase() === 'object' || ($this.attr('height') && !isNaN(parseInt($this.attr('height'), 10))) ) ? parseInt($this.attr('height'), 10) : $this.height(),
								width = !isNaN(parseInt($this.attr('width'), 10)) ? parseInt($this.attr('width'), 10) : $this.width(),
								aspectRatio = height / width;
						if(!$this.attr('name')){
							var videoName = 'fitvid' + $.fn.fitVids._count;
							$this.attr('name', videoName);
							$.fn.fitVids._count++;
						}
						$this.wrap('<div class="fluid-width-video-wrapper"></div>').parent('.fluid-width-video-wrapper').css('padding-top', (aspectRatio * 100)+'%');
						$this.removeAttr('height').removeAttr('width');
					});
				});
			};

			// Internal counter for unique video names.
			$.fn.fitVids._count = 0;

			// Works with either jQuery or Zepto
		})( window.jQuery || window.Zepto );
	</script>
	<script src="https://cdn.jsdelivr.net/sweetalert2/3.2.3/sweetalert2.min.js"></script>
	<script src="{{ url('/js/jquery.form.min.js')}}"></script>
	<script src="{{ url('/js/draggabilly.pkgd.js') }}"></script>
	<script src="{{ url('/js/packery.pkgd.min.js') }}"></script>
	<script src="{{ url('/js/nouislider.min.js') }}"></script>
	<script src="{{ url('/js/dragLayout.js') }}"></script>
	<script>
		// 2. This code loads the IFrame Player API code asynchronously.
		var tag = document.createElement('script');

		tag.src = "https://www.youtube.com/iframe_api";
		var firstScriptTag = document.getElementsByTagName('script')[0];
		firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

		// 3. This function creates an <iframe> (and YouTube player)
		//    after the API code downloads.
		function onYouTubeIframeAPIReady() {}
	</script>

@endsection