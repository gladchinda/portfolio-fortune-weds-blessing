<section id="attending-event" class="container-fluid" ng-controller="fbAttendingController">
    <div class="row">
        <div class="col-md-5 col-md-offset-2">
            <div class="attending-bold text-center">
                <span class="text-uppercase">Attending</span>
            </div>
            <div class="attending-choice">
                <ul class="choice-list">
                    <fb-attending-choice ng-repeat="item in choices" choice-label="@{{item}}"></fb-attending-choice>
                </ul>
            </div>
        </div>
        <div class="attending-count-container col-md-3">
            <div class="attending-count">
                <span class="main-counter">160</span>
            </div>
            <div class="count-desc">
                <span class="text-uppercase">People are attending</span>
            </div>
        </div>
    </div>
</section>
