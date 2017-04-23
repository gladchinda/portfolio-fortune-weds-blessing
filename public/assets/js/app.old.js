angular.module('main-app', []);

$(function($) {

	(function handleWindowScroll() {

		var $navbar = $('#global-nav');
		var $navbarContainer = $('.container-fluid', $navbar).first();
		var $navbarInnerHeight = $navbar.innerHeight();
		var $scrollHeight = parseInt($navbarContainer.css('top'));

		var $hero = $('#cover-hero');
		var $heroHeight = $hero.innerHeight();
		var $heroDescendants = $hero.find('.hero-banner');

		var $storyBoard = $('#story-board');
		var $storyElems = $storyBoard.find('.story-board-header').siblings();
		var $storyHeight = $storyBoard.outerHeight() / 2;

		$(window).on('scroll resize', function(e) {

			var $windowScrollHeight = $(this).scrollTop();

			var $shouldPinNavbar = $windowScrollHeight >= $scrollHeight;
			var $alreadyPinnedNavbar = $navbar.hasClass('navbar-fixed-top');
			var $togglePinnedNavbar = !!($shouldPinNavbar ^ $alreadyPinnedNavbar);

			var $navbarRevealHeight = $navbarInnerHeight / 1.5;
			var $navbarTransparency = $shouldPinNavbar ? ( Math.min($windowScrollHeight - $scrollHeight, $navbarRevealHeight) / $navbarRevealHeight ).toFixed(2) : (1 - ( Math.min($windowScrollHeight, $scrollHeight) / $scrollHeight ).toFixed(2));

			var $heroTransparency = 1 - Math.sinh(Math.min($heroHeight, $windowScrollHeight) / $heroHeight * Math.asinh(1)).toFixed(2);

			var $storyTransparency = ( (Math.pow(11, Math.max(0, Math.min($storyHeight, $windowScrollHeight - $heroHeight / 5)) / $storyHeight) - 1) / 10 ).toFixed(2);

			$navbar.css({
				filter: "alpha(opacity=" + $navbarTransparency * 100 + ")",
				opacity: $navbarTransparency,
			});

			$storyBoard.find('.story-body').css({
				filter: "alpha(opacity=" + Math.pow($storyTransparency, 3) * 100 + ")",
				opacity: $storyTransparency,
			});

			$storyBoard.find('.story-board-header').css({
				transform: "translateY(" + -Math.pow($storyTransparency, 3) * 30 + "px)",
			});

			$storyElems.css({
				transform: "translateY(" + -Math.pow($storyTransparency, 3) * 80 + "px)",
			});

			// $heroDescendants.each(function() {
			// 	$(this).css({
			// 		filter: "alpha(opacity="+ $heroTransparency * 100 + ")",
			// 		opacity: $heroTransparency,
			// 	});
			// });

			$togglePinnedNavbar && $navbar.toggleClass('navbar-fixed-top');
		});

	})();
});