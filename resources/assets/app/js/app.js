(function($, angular) {

    angular.module('app.filters', [])

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

    angular.module('fb.couple', [])

        .controller('fbCoupleController', ['$scope', function($scope) {
            $scope.couple = {
                'groom': {
                    'name': 'Fortune Chinda',
                    'who': 'groom',
                    'avatar': '/assets/img/groom-avatar.jpg',
                    'social': {
                        'facebook': '#',
                        'twitter': '#',
                        'instagram': '#',
                    }
                },
                'bride': {
                    'name': 'Blessing Peter',
                    'who': 'bride',
                    'avatar': '/assets/img/bride-avatar.jpg',
                    'social': {
                        'facebook': '#',
                        'twitter': '#',
                        'instagram': '#',
                    }
                },
            };
        }])

        .directive('fbCoupleProfile', function() {
            return {
                restrict: 'AE',
                replace: true,
                scope: {
                    'person': "=coupleWho",
                },
                templateUrl: '/assets/js/templates/couple-profile.html',
                transclude: true,
            };
        });

    angular.module('fb', ['app.filters', 'fb.couple', 'fb.attending']);

    $(function app() {});

})(jQuery, angular);
