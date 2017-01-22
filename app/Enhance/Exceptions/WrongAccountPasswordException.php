<?php

namespace App\Enhance\Exceptions;

use Exception;
use App\Models\Account;

class WrongAccountPasswordException extends Exception
{
	protected $account;

	public function __construct(Account $account, $message = null)
	{
		$this->account = $account;
		$message = $message ?: 'Incorrect password.';
		parent::__construct($message);
	}

	public function getAccount()
	{
		return $this->account;
	}
}
