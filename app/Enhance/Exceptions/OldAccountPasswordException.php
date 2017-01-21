<?php

namespace App\Enhance\Exceptions;

use Exception;
use App\Models\Account;

class OldAccountPasswordException extends Exception
{
	protected $account;

	public function __construct(Account $account, $message = null)
	{
		$this->account = $account;
		$message = $message ?: 'Cannot reuse old password.';
		parent::__construct($message);
	}

	public function getAccount()
	{
		return $this->account;
	}
}
