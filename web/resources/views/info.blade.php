@extends('layouts.app')

@section('pageCss')
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
    <div class="infoContainer container">
        <div class="col-md-12">
            <h1>Contact</h1>
            <h3>We helpen je graag verder!</h3>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="subject">Onderwerp</label>
                    <input type="text" id="subject" name="subject" class="form-control input-lg"
                           placeholder="Onderwerp">
                </div>
                <div class="form-group">
                    <label for="description">Boodschap</label>
                        <textarea name="description" id="description" class="form-control"
                                  maxlength="600"></textarea>
                </div>
                <button type="submit" class="btn btn-primary pull-right messageButton">Verzenden</button>
            </div>
            <div id="contact" class="col-md-6">
                <div id="address" class="col-md-12">
                    <div class="col-md-4 icon">
                        <i class="fa fa-map-marker" aria-hidden="true"></i>
                    </div>
                    <div class="col-md-8">
                        <h3>Stadhuis</h3>
                        <p>Grote Markt 1, 2000 Antwerpen</p>
                    </div>
                </div>
                <div id="teleNumber" class="col-md-12">
                    <div class="col-md-4 icon">
                        <i class="fa fa-phone" aria-hidden="true"></i>
                    </div>
                    <div class="col-md-8">
                        <h3>03 22 11 333</h3>
                    </div>
                </div>
                <div id="emailInfo" class="col-md-12">
                    <div class="col-md-4 icon">
                        <i class="fa fa-envelope" aria-hidden="true"></i>
                    </div>
                    <div class="col-md-8">
                        <h3>info@stad.antwerpen.be</h3>
                    </div>
                </div>
                <div id="social-media" class="col-md-12">
                    <div id="iconsSocialMedia" class="col-md-10 col-md-offset-2">
                        <div>
                            <a href="https://twitter.com/Stad_Antwerpen"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                            <a href="https://www.facebook.com/stad.antwerpen"><i class="fa fa-facebook-official" aria-hidden="true"></i></a>
                            <a href="https://www.instagram.com/stad_antwerpen/"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                        </div>
                    </div>

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