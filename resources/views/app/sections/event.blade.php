<section id="event-section" class="container-fluid">
    <div class="row" ng-controller="fbEventsController">
        <fb-event-board event-type="events.traditional"></fb-event-board>
        <fb-event-board event-type="events.wedding"></fb-event-board>
    </div>
    <div id="event-locations-section" class="row" ng-controller="fbLocationsController">
        <fb-event-location ng-repeat="location in locations"></fb-event-location>
    </div>
</section>
