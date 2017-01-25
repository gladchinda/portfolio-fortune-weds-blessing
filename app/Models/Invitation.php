<?php

namespace App\Models;

use Carbon;
use Keygen;
use InvalidArgumentException;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
	protected $appends = ['sender'];

	protected $hidden = ['from'];

	protected $dates = ['created', 'followed'];

	public $timestamps = false;

    public function isVia($via)
    {
        return in_array($via, ['phone', 'email']) ? ($this->via == $via) : false;
    }

    public function getFromAttribute($value)
    {
        return Account::find($value);
    }

    public function getSenderAttribute()
    {
        return $this->from->fullname;
    }

    public function setRecipientAttribute($value)
    {
    	if (filter_var($value, FILTER_VALIDATE_EMAIL)) {

    		$this->attributes['via'] = 'email';
    		$this->attributes['recipient'] = strtolower($value);

    	} else {

    		$phone = str_replace($symbols, '', $value);

    		if (! preg_match('/^\d{9,}$/', $phone)) {
    			throw new InvalidArgumentException("Invalid recipient phone number.");
    		}

    		$this->attributes['via'] = 'phone';
    		$this->attributes['recipient'] = $phone;

    	}
    }

    public function getRecipientAttribute($value)
    {
        return ( $this->isVia('phone') ? '+' : '' ) . $value;
    }

    public function getInvitationUrl()
    {
        $base = "https://fortunewedsblessing.com/invitations";

        return sprintf("%s?token=%s", $base, $this->generateToken());
    }

    protected function getRecipientCodeForToken()
    {
    	$recipient = $this->recipient;

        if ($this->isVia('email')) {

        	list($emailUser, $emailDomain) = sscanf($recipient, "%s@%s");

        	$emailUser = array_map(function ($chr) {
		        return ord($chr);
        	}, str_split($emailUser));

        	$emailDomain = array_map(function ($chr) {
		        return ord($chr);
        	}, str_split($emailDomain));

        	$countEmailUser = count($emailUser);
        	$countEmailDomain = count($emailDomain);

        	$recipient = (string) round(array_sum($emailUser) / $countEmailUser);

        	$diff = $countEmailUser + $countEmailDomain;

        	$recipient .= ( strlen($diff) < 2 ? '0' : '' ) . $diff;
        	$recipient .= round(array_sum($emailDomain) / $countEmailDomain);
        }

        return $recipient;
    }

    protected function generateToken()
    {
        $random = Keygen::numeric(3)->generate();
        $invert = (bool) (($random % 2) & 1);

        $code = $this->code;
        $code = $invert ? strrev($code) : $code;
        $code4 = substr($code, -4);

        $id = $this->id;
        $idMax = (int) ($id > $code4);
        $id = abs($id - $code4);

        $recipient = strrev(substr($this->getRecipientCodeForToken(), -(4 + $invert), 4));
        $timestamp = strrev(substr($this->created->format('U'), 2));

        while (strlen($id) < 4) {
        	$id = "0{$id}";
        }

        $fragments = $invert ? [$recipient, $code, $id, $timestamp, $random, $idMax]
        			: [$timestamp, $id, $recipient, $code, $random, $idMax];

        $token = array_map(function ($code) {
            return chr((int) $code);
        }, str_split(join('', $fragments), 2));

        return bin2hex(join('', $token));
    }

    public static function getInvitationFromToken($token)
    {
    	if (preg_match('/^[a-f0-9]{26}$/', $token)) {

			$inverse = array_map(function ($chr) {
		        $code = (string) ord($chr);
		        return (strlen($code) < 2 ? '0' : '') . $code;
		    }, str_split(hex2bin($token)));

			$inverse = join('', $inverse);

			$random = substr($inverse, -4, 3);
		    $invert = (bool) ($random % 2) & 1;
		    $idMax = (bool) substr($inverse, -1);

			$breaks = $invert ? [4, 6, 4, 8] : [8, 4, 4, 6];

			for ($i = 0, $j = 0, $k = count($breaks); $i < $k; $i++) {
				$_len = $breaks[$i];
				$breaks[$i] = substr($inverse, $j, $_len);
				$j += $_len;
			}

			$code = $breaks[ $invert ? 1 : 3 ];
			$code4 = substr($code, -4);
			$code = $invert ? strrev($code) : $code;

			$id = $breaks[ $invert ? 2 : 1 ];
			$id = $code4 + ( $id * ( ($idMax) ? 1 : -1 ) );

			$recipient4 = strrev($breaks[ $invert ? 0 : 2 ]);

			$timestamp = substr(Carbon::now()->format('U'), 0, 2) . strrev($breaks[ $invert ? 3 : 0 ]);
			$timestamp = Carbon::createFromFormat('U', $timestamp);

			$invitations = static::whereId($id)->whereCode($code)->get()->filter(function ($iv) use ($timestamp, $recipient4, $invert) {

				$isTimestamp = $iv->created->eq($timestamp);

				$recipient = substr($iv->getRecipientCodeForToken(), -(4 + $invert), 4);
				$isRecipient = $recipient == $recipient4;

				return $isTimestamp && $isRecipient;

			});

			if (count($invitations) > 0) {
				return $invitations->first();
			}
    	}
			
    	throw new InvalidArgumentException("Invalid invitation token.");
    }

    public static function getInvitationFromUrl($url)
    {
    	if ($url = parse_url($url)) {

    		parse_str($url['query'], $query);

    		$isBase = $url['host'] == 'fortunewedsblessing.com';
    		$isPath = $url['path'] == '/invitations';

    		if ($isBase && $isPath && isset($query['token'])) {
    			return static::getInvitationFromToken($query['token']);
    		}
    	}
			
    	throw new InvalidArgumentException("Invalid invitation URL.");
    }

    public function getAllInvitationsForRecipient()
    {
		$recipient = substr($this->recipient, $this->isVia('phone') ? 1 : 0);

		return static::whereRecipient($recipient)->get();
    }

    public function scopeVia($query, $via)
    {
    	return in_array($via, ['phone', 'email']) ? $query->whereVia($via) : $query;
    }

    public function scopeFollowed($query)
    {
    	return $query->whereNotNull('followed');
    }

    public function scopeAccepted($query)
    {
    	return $query->followed()->whereStatus('accepted');
    }

    public function scopeRejected($query)
    {
    	return $query->followed()->whereStatus('rejected');
    }
}
