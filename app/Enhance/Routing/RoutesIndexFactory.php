<?php

namespace App\Enhance\Routing;

use BadMethodCallException;
use Illuminate\Support\Str;
use InvalidArgumentException;
use App\Enhance\Routing\Indexes\ApiRoutesIndex;
use App\Enhance\Routing\Indexes\WebRoutesIndex;

class RoutesIndexFactory
{
	protected function loadRoutes(RoutesIndexInterface $index)
	{
		$index->loadRoutes();
		return $this;
	}

	protected function loadApiRoutes()
	{
		return $this->loadRoutes(new ApiRoutesIndex);
	}

	protected function loadWebRoutes()
	{
		return $this->loadRoutes(new WebRoutesIndex);
	}

	public function load($index)
	{
		$args = func_get_args();

		foreach ($args as $index) {
			if (!is_string($index)) {
				throw new InvalidArgumentException('Invalid index name.');
			}

			$this->__call($index, []);
		}

		return $this;
	}

	public function __call($index, $args)
	{
		$method = sprintf("load%sRoutes", Str::studly($index));

		if (method_exists($this, $method)) {
			return call_user_func_array([$this, $method], $args);
		}

		throw new BadMethodCallException("Undefined route index[{$index}].");
	}
}
