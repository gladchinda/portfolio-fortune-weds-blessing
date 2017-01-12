<?php

namespace App\Enhance\Routing\Indexes;

class WebRoutesIndex extends AbstractRoutesIndex
{
	public function loadRoutes()
	{
		$this->router->get('/', function() {
			return view('index');
		});
	}
}
