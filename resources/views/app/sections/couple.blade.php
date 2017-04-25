<section id="couple-section" class="container-fluid">
    <div class="row">
        <div id="photos-section" class="col-md-7">
            <div class="row">
                <div class="photo-icon col-xs-2 col-xs-offset-5">
                    <img class="img-responsive" src="https://s3.eu-west-2.amazonaws.com/fortunewedsblessing.com/images/static/1492981678_8407d85e85f00e401cf7797959984250.png" alt="Aperture icon">
                </div>
                <div class="col-xs-12">
                    <div class="photo-title text-center text-uppercase">Photo Gallery</div>
                    <div class="photo-grid"></div>
                    <button class="text-uppercase btn btn-lg photo-gallery-btn" data-toggle="modal" data-target="#photo-gallery-modal">Photo Gallery</button>
                </div>
            </div>
        </div>
        <div id="about-couple-section" class="col-md-5" ng-controller="fbCoupleController">
            <div class="couple-row row">
                <fb-couple-profile couple-who="couple.groom" ng-cloak></fb-couple-profile>
                <fb-couple-profile couple-who="couple.bride" ng-cloak></fb-couple-profile>
            </div>
        </div>
    </div>
</section>
