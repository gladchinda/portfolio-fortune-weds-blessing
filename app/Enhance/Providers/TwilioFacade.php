<?php

namespace App\Enhance\Providers;

use Twilio\Rest\Client as Twilio;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Illuminate\Routing\UrlGenerator
 */
class TwilioFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Twilio::class;
    }

    public static function message($to, $message = null)
    {
    	if (is_string($message) && ! empty($message)) {
	    	$twilio = resolve(Twilio::class);
	    	$from = config('services.twilio.number');

	    	return $twilio->messages->create($to, [
	    		'from' => $from,
	    		'body' => $message,
	    	]);
	    }
    }
}
