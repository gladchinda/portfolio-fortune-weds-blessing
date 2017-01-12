<?php

namespace App\Enhance;

use Exception;
use App\Enhance\Routing\RoutesIndexFactory;

class EnhancedServiceFactory
{
	public static function routes($index = null)
	{
		$args = func_get_args();
		$factory = new RoutesIndexFactory;
		
		return empty($args) ? $factory : call_user_func_array([$factory, 'load'], $args);
	}
}
