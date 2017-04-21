(function($, angular) {

    angular.module('fb.app', [])

        .constant('baseUrl', "/services/http/rest/json/")

        .factory('httpDataLoader', ['$http', 'baseUrl', function($http, baseUrl) {

            var loadData = function(endpoint, scope, key) {
                $http({
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

    angular.module('fb.photos', []);

    angular.module('fb', ['ngSanitize', 'fb.app', 'fb.couple', 'fb.attending', 'fb.events', 'fb.locations', 'fb.photos']);

    $(function app() {});

})(jQuery, angular);
