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
	}
}
