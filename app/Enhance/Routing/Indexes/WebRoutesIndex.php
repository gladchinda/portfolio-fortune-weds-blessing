<?php

namespace App\Enhance\Routing\Indexes;

class WebRoutesIndex extends AbstractRoutesIndex
{
	public function loadRoutes()
	{
		$R = $this->router;

		$R->get('/', function() {
			return view('index');
		});

		$R->group(["prefix" => '/services/http/rest/json'], function() use ($R) {

			$R->get('/couple', function() {

				$couple = [
					'groom' => [
						'name' => 'Fortune Chinda',
	                    'who' => 'groom',
	                    'avatar' => '/assets/img/groom-avatar.jpg',
	                    'social' => [
	                        'facebook' => 'https://web.facebook.com/gerachblings',
	                        'twitter' => 'https://twitter.com/gerachblings',
	                        'instagram' => 'https://www.instagram.com/gerachblings/',
	                    ],
						'profile' => '<strong>Fortune Chinda</strong>, also known as <strong>(Phortunez)</strong>, is a young gentleman from Rivers State, Nigeria. He is an artist, author, idea and brand expert, design engineer and planner, dynamic songwriter, music instructor, and teens coach. He is the <strong>CEO/Creative Director</strong> of Innovation House Company. He is also a <strong>Team Leader</strong> at Mould The Young Foundation, and a <strong>Recording Artist</strong> at Phortunez &amp; GospelMix Family.',
		            ],
	                'bride' => [
						'name' => 'Blessing Peter',
	                    'who' => 'bride',
	                    'avatar' => '/assets/img/bride-avatar.jpg',
	                    'social' => [
	                        'facebook' => 'https://web.facebook.com/blessing.peter.56829',
	                        'twitter' => 'https://twitter.com/peterzblessing',
	                        'instagram' => 'https://www.instagram.com/blizzbeautified/',
	                    ],
						'profile' => '<strong>Blessing Peter</strong> is a young lady with a great personality. She hails from Edo State, Nigeria. She is passionate about inspiring the younger ones; keeping them in perspective and moving their lives in a positive direction. She is a biochemist, strategist, professional hair stylist, make-up artist, young people\'s coach, and amazing writer. She is the <strong>Creative Director</strong> of Blizz Beautified Hair Styling, Braiding &amp; Make-Up Finery, and also a <strong>Project Assistant</strong> at Innovation House Company &amp; Mould The Young Foundation.',
	                ],
				];

				return response()->json($couple);

			});

			$R->get('/events', function() {

				$events = [
					'traditional' => [
		                'label' => 'Traditional Marriage',
		                'code2' => 'tm',
		                'date' => '2017-04-18',
		                'colors' => ['wine', 'cream'],
		            ],
	                'wedding' => [
	                    'label' => 'White Wedding',
	                    'code2' => 'wd',
	                    'date' => '2017-04-29',
	                    'colors' => ['teal', 'peach'],
	                ],
				];

				return response()->json($events);

			});

			$R->get('/locations', function() {

				$locations = [
					[
						'event' => 'Traditional Marriage',
	                    'place' => 'No. 5 Idumoza Street, Iruekpen',
	                    'address' => 'Ekpoma, Edo State',
	                    'mapUrl' => "https://goo.gl/maps/4Qjp4pCtePR2",
		            ],
	                [
						'event' => 'White Wedding',
	                    'place' => 'RCCG, Glory House Parish, Rivers Province 3, PHC',
	                    'address' => 'Plot 8, Okworo, Standard Road, Elelenwo, Port Harcourt',
	                    'mapUrl' => "https://goo.gl/maps/5Anwhp2xL1v",
	                ],
	                [
						'event' => 'Wedding Reception',
	                    'place' => 'PN Events Place (former Pend Motels)',
	                    'address' => 'No. 54 - 56 Old Refinery Road, Elelenwo, PHC',
	                    'mapUrl' => "https://goo.gl/maps/C1hrSEbVpfx",
	                ],
				];

				return response()->json($locations);

			});
		});
	}
}
