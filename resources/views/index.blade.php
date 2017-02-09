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

    <div class="container-fluid" style="min-height:800px;">
        <div class="row" style="padding:0 200px;">
            <div class="col-md-6 col-xs-12">Hello</div>
            <div class="col-md-6 col-xs-12">There</div>
        </div>
    </div>
@endsection