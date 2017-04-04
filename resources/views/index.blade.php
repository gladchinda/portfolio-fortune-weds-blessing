@extends('app.layout')

@section('content')
    @parent
    
    <!-- begin navbar -->
    <nav id="global-nav" class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#global-nav-collapsible" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">
                    <img src="http://localhost/www/zynes/eway/ui/app/images/139426.png" alt="fortune-weds-blessing">
                    <span class="hidden-xs hidden-sm hidden-md brand-label">fortunewedsblessing</span>
                </a>
            </div>
            <div id="global-nav-collapsible" class="collapse navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">About <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">The Bride</a></li>
                            <li><a href="#">The Groom</a></li>
                            <li><a href="#">Our Story</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Feeds</a></li>
                    <li><a href="#">Events</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- end navbar -->

    <!-- begin hero -->
    <div id="cover-hero" class="hero" role="banner">
        <div class="hero-container">
            <div class="hero-banner">
                <h1>Fortune weds Blessing</h1>
                <a href="#" class="btn btn-lg btn-primary hero-action-btn">Event Schedules</a>
            </div>
        </div>
    </div>
    <!-- end hero -->

    <div class="container-fluid">
        <section id="story-board" class="row">
            <div class="text-center story-board-header">
                <span class="text-uppercase small-title">The Beginning</span>
                <span class="main-title">Our Story</span>
            </div>
            <p class="lead text-center story-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
            <div id="meet-the-couple" class="col-md-8 col-md-offset-2">
                <div class="row">
                    <div class="col-md-6">
                        <div class="couple-box">
                            <div class="photo-cover">
                                <img class="img-responsive" src="http://localhost/www/zynes/eway/ui/app/images/img.jpg" alt="...">
                                <div class="couple-label">
                                    <span class="text-uppercase couple-which">The Groom</span>
                                    <span class="couple-name">Fortune Chinda</span>
                                </div>
                            </div>                        
                            <button type="button" class="btn btn-primary view-profile-btn">Meet the Groom</button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="couple-box couple-box-right">
                            <div class="photo-cover">
                                <img class="img-responsive" src="http://localhost/www/zynes/eway/ui/app/images/img.jpg" alt="...">
                                <div class="couple-label">
                                    <span class="text-uppercase couple-which">The Bride</span>
                                    <span class="couple-name">Blessing Peter</span>
                                </div>
                            </div>                        
                            <button type="button" class="btn btn-primary view-profile-btn">Meet the Bride</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section id="events-location-map" class="row">
            <div class="col-md-4 events-map-sidebar">
                <div class="col-md-12 events-btn-group">
                    <div class="btn-group btn-group-justified" role="group" aria-label="events-button-group">
                        <div class="btn-group active">
                            <button type="button" class="btn btn-default">Traditional</button>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default">Wedding</button>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default">Reception</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 event-cover-banner">
                    <img class="img-responsive" src="http://localhost/www/zynes/eway/ui/app/images/img.jpg" alt="">
                    <div class="event-title">Traditional Marriage</div>
                </div>
                <div class="col-md-12" style="padding: 0 5%; color: #FFF;">
                    <!-- <div class="row">
                        <div class="col-md-12" style="border-bottom: 1px solid #4A5D6D; min-height: 130px;">
                            <div class="location-info-header" style="padding-top: 20px;">
                                <span class="glyphicon glyphicon-map-marker"></span>
                                <strong style="position: relative; left: 5px; bottom: 1px;">Location</strong>
                            </div>
                            <div class="" style="color: #CDE; font-size: 18pt; padding: 5px 0 20px;">Portharcourt, Rivers State</div>
                        </div>
                        <div class="col-md-6" style="border-right: 1px solid #4A5D6D;">
                            <div class="location-info-header" style="padding-top: 20px;">
                                <span class="glyphicon glyphicon-map-marker"></span>
                                <strong style="position: relative; left: 5px; bottom: 1px;">Date</strong>
                            </div>
                            <div class="" style="color: #CDE; font-size: 22pt; padding: 5px 0 20px;">March 16</div>
                        </div>
                        <div class="col-md-6">
                            <div class="location-info-header" style="padding-top: 20px;">
                                <span class="glyphicon glyphicon-map-marker"></span>
                                <strong style="position: relative; left: 5px; bottom: 1px;">Time</strong>
                            </div>
                            <div class="" style="color: #CDE; font-size: 22pt; padding: 5px 0 20px;">12:00 PM</div>
                        </div>
                    </div> -->
                </div>
            </div>
            <div class="col-md-8 events-map-container">
                <div id="events-map-canvas"></div>
            </div>
        </section>
    </div>
@endsection