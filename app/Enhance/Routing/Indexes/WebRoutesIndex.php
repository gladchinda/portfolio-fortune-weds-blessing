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

		$R->post('/services/http/rest/photo/upload', 'Rest\MainController@uploadPhoto');

		$R->group(["prefix" => '/services/http/rest/json', "namespace" => "Rest"], function() use ($R) {

			$R->get('/couple', 'MainController@getCoupleData');
			$R->get('/events', 'MainController@getEventsData');
			$R->get('/photos', 'MainController@getPhotosData');
			$R->get('/locations', 'MainController@getLocationsData');

		});
	}
}
