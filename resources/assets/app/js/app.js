(function($, angular) {

    $(function app() {

        (function mapViewerController() {

            var $modalElem = $('#map-view-modal'),
                $modalContent = $modalElem.find('.modal-content'),
                $loadingSpinner = $modalElem.find('.loading-spinner').first(),
                $googMapFrame = $modalContent.find('iframe.goog-map-embed-frame').first();

            $modalElem.on('shown.bs.modal', function(evt) {

                var $mapEmbedUrl = $(evt.relatedTarget).data('place');

                function googMapFrameResolution() {

                    var $googMapFrameAttributes = {
                        height: $modalContent.height() - 5,
                        width: $modalContent.width(),
                    };

                    $.each($googMapFrameAttributes, function(attr, val) {
                        $googMapFrame.attr(attr, val);
                    });

                }

                googMapFrameResolution();

                $googMapFrame.prop('src', $mapEmbedUrl).on('load', function(evt) {
                    $loadingSpinner.hide();
                });

                $(window).on('resize', function(evt) {
                    googMapFrameResolution();
                    $modalElem.modal('handleUpdate');
                });

            })

            .on('show.bs.modal', function(evt) {
                $loadingSpinner.show();
            })

            .on('hidden.bs.modal', function(evt) {
                $googMapFrame.removeAttr('src');
                $loadingSpinner.hide();
            });

        })();

        (function loadGalleryPhotos() {

            $.ajax({
                url: '/services/http/rest/json/photos',
            }).done(function (result) {

                var $linksContainer = $('#blueimp-photo-links'),
                    $baseUrl = 'https://s3.eu-west-2.amazonaws.com/fortunewedsblessing.com/images/uploads/',
                    $photoGrid = $('#photos-section .photo-grid');

                (function getRandomShowcasePhotos(length) {

                    var length = length || 7,
                        $randomPhotos = [];

                    while ($randomPhotos.length < length) {
                        $random = Math.floor( Math.random() * result.photos.length );
                        ( $randomPhotos.indexOf($random) === -1 ) && $randomPhotos.push($random);
                    }

                    $randomPhotos = $randomPhotos.map(function(n) {
                        return $baseUrl + 'thumbnails/' + result.photos[n];
                    });

                    $.each($randomPhotos, function(index, photo) {
                        $photoGrid.append($('<div class="photo-grid-img"/>').append($('<img class="img-responsive">').prop('src', photo).attr('alt', 'Gallery sample photo')));
                    });

                })();

                $.each(result.photos, function (index, photo) {

                    $('<a/>')
                        .append($('<img>').prop('src', $baseUrl + 'thumbnails/' + photo))
                        .prop('href', $baseUrl + photo)
                        .attr('data-gallery', '')
                        .appendTo($linksContainer);

                });

            });

        })();

        (function photoGalleryController() {

            var $modalElem = $('#photo-gallery-modal'),
                $modalContent = $modalElem.find('.modal-content'),
                $linksContainer = $('#blueimp-photo-links'),
                $galleryContainer = $('#blueimp-gallery');

            $modalElem.on('shown.bs.modal', function(evt) {

                function photoGridResolution() {

                    var $fullWidth = $modalContent.width(),
                        $fullHeight = $modalContent.height();

                    $linksContainer.css({'width': $fullWidth, 'height': $fullHeight * 0.5}).perfectScrollbar({maxScrollbarLength: $fullHeight * 0.2});

                }

                $(window).on('resize', function(evt) {
                    photoGridResolution();
                });

                photoGridResolution();

            });

        })();

    });

    (function runAngularApplication() {

        angular.module('fb.app', [])

            .constant('baseUrl', "/services/http/rest/json/")

            .factory('httpDataLoader', ['$http', 'baseUrl', function($http, baseUrl) {

                var loadData = function(endpoint, scope, key) {
                    return $http({
                        method: 'GET',
                        url: baseUrl + endpoint,
                        cache: true,
                    }).then(function(response) {
                        scope[key] = response.data;
                    });
                };

                return {
                    loadData: loadData,
                };

            }])

            .filter('initialCaps', function() {

                return function(string) {

                    if (angular.isString(string)) {

                        return string.split(' ').map(function(s) {

                            return s = s.toLowerCase().split(''), s[0].toUpperCase() + s.splice(1).join('');

                        }).join(' ');

                    }
                };
            });

        angular.module('fb.attending', [])

            .controller('fbAttendingController', ['$scope', function($scope) {
                $scope.choices = ['yes', 'maybe', 'no'];
            }])

            .factory('attendingChoiceService', function() {

                var currentChoice = null,
                    currentChoiceElem = null,

                    setCurrentChoice = function(elem) {

                        var current = this.currentChoiceElem,
                            elem = angular.isElement(elem) ? angular.element(elem) : null;

                        if (elem && elem.attr('choice-label')) {

                            current && current.removeClass('active');

                            this.currentChoice = elem.attr('choice-label');
                            this.currentChoiceElem = elem.addClass('active');
                        }

                    };

                return {
                    currentChoice: currentChoice,
                    currentChoiceElem: currentChoiceElem,
                    setCurrentChoice: setCurrentChoice,
                };
            })

            .directive('fbAttendingChoice', ['attendingChoiceService', function($attendingChoiceService) {
                return {
                    restrict: 'AE',
                    scope: {
                        'label': "@choiceLabel"
                    },
                    link: function(scope, elem, attrs) {

                        var currentElem = null;

                        elem.find('.choice-checker, .choice-label').on('mouseover', function(e) {

                            elem.addClass('has-focus');

                        }).on('mouseout', function(e) {

                            elem.removeClass('has-focus');

                        }).on('click', function(e) {

                            if (!elem.hasClass('active')) {
                                $attendingChoiceService.setCurrentChoice(elem);
                            }

                        });

                    },
                    replace: true,
                    templateUrl: '/assets/js/templates/attending-choice.html',
                };
            }]);

        angular.module('fb.couple', ['fb.app'])

            .controller('fbCoupleController', ['$scope', 'httpDataLoader', function($scope, httpDataLoader) {

                httpDataLoader.loadData('couple', $scope, 'couple');

            }])

            .directive('fbCoupleProfile', function() {
                return {
                    restrict: 'AE',
                    replace: true,
                    scope: {
                        'person': "=coupleWho",
                    },
                    templateUrl: '/assets/js/templates/couple-profile.html',
                };
            });

        angular.module('fb.events', ['fb.app'])

            .controller('fbEventsController', ['$scope', 'httpDataLoader', function($scope, httpDataLoader) {

                httpDataLoader.loadData('events', $scope, 'events');

            }])

            .directive('fbEventBoard', function() {
                return {
                    restrict: 'AE',
                    replace: true,
                    scope: {
                        'event': "=eventType",
                    },
                    templateUrl: '/assets/js/templates/events.html',
                };
            })

            .directive('fbEventColor', function() {
                return {
                    restrict: 'AE',
                    replace: true,
                    scope: true,
                    templateUrl: '/assets/js/templates/event-color.html',
                    transclude: true,
                };
            });

        angular.module('fb.locations', ['fb.app'])

            .controller('fbLocationsController', ['$scope', 'httpDataLoader', function($scope, httpDataLoader) {

                httpDataLoader.loadData('locations', $scope, 'locations');

            }])

            .directive('fbEventLocation', function() {
                return {
                    restrict: 'AE',
                    replace: true,
                    scope: true,
                    templateUrl: '/assets/js/templates/locations.html',
                };
            });

        angular.module('fb.credits', [])

            .controller('fbCreditsController', ['$scope', 'httpDataLoader', '$window', function($scope, httpDataLoader, $window) {

                httpDataLoader.loadData('credits', $scope, 'credits').then(function() {

                    $window['jQuery']('#credits-and-supports .typewriter').first().typed({
                        strings: $scope['credits'].supporters,
                        loop: true,
                        typeSpeed: 20,
    	                backDelay: 2500,
                    });

                });

            }]);

        angular.module('fb', ['ngSanitize', 'fb.app', 'fb.couple', 'fb.attending', 'fb.events', 'fb.locations', 'fb.credits']);

    })();

})(jQuery, angular);
