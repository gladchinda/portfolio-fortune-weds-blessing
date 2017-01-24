<?php

namespace App\Listeners;

use Twilio;
use App\Events\PersonInvited;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendInvitationSMS
{
    protected $message;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->setMessageTemplate();
    }

    protected function setMessageTemplate()
    {
        $this->message = "Hello%s, you are invited to the wedding program of Goodfortune Chinda and Blessing Peter on March 11, 2017. Please follow this link for more information: %s";
    }

    protected function getCompiledMessage(PersonInvited $event)
    {
        $invitation = $event->getInvitation();

        $fullname = $invitation->fullname;
        $fullname = $fullname ? " {$fullname}" : '';

        $url = $invitation->getInvitationUrl();

        return sprintf($this->message, $fullname, $url);
    }

    /**
     * Handle the event.
     *
     * @param  PersonInvited  $event
     * @return void
     */
    public function handle(PersonInvited $event)
    {
        $invitation = $event->getInvitation();

        if ($invitation->isVia('phone')) {

            $phone = $invitation->recipient;
            $message = $this->getCompiledMessage($event);

            // return Twilio::message($phone, $message);
        }
    }
}
