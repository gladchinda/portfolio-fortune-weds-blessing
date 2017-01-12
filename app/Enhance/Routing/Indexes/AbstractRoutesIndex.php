<?php

namespace App\Enhance\Routing\Indexes;

use App\Enhance\Routing\RoutesIndexInterface;

abstract class AbstractRoutesIndex implements RoutesIndexInterface
{
	protected $router;

	public function __construct()
	{
		$this->router = app('router');
	}

	abstract public function loadRoutes();
}
