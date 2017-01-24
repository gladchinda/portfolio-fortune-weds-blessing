<?php

namespace App\Http\Controllers\Core;

use DB;
use Auth;
use Carbon;
use App\Models\Email;
use App\Models\Account;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAccountRequest;
use App\Http\Controllers\Core\Support\PasswordManager;

class AccountController extends Controller
{
    use PasswordManager;

    protected function getAuthenticatedAccount()
    {
    	return Account::find(10);
    	return Auth::user();
    }

    protected function createAccountEmail($email)
    {
    	$email = Email::unguarded(function() use ($email) {
    		return Email::firstOrCreate(['email' => $email]);
    	});

    	if (Account::whereEmail($email->id)->count() > 0) {
    		throw new \Exception('Email already exists.');
    	}

    	return $email;
    }

    protected function assertUniqueUsername($username)
    {
    	if (Account::whereUsername($username)->count() > 0) {
    		throw new \Exception('Username not available.');
    	}
    }

    protected function normalizeAccountName($firstname = null, $lastname = null)
    {
    	$names = [];
    	$args = func_get_args();

    	foreach ($args as $name) {
    		if (is_string($name) && preg_match('/^[a-z]+$/i', $name)) {
    			array_push($names, $name);
    		}
    	}

    	return join(' ', $names) ?: null;
    }

    public function getAccount(Request $request)
    {
    	return $this->getAuthenticatedAccount();
    }

    public function createAccount(CreateAccountRequest $request)
    {
    	return DB::transaction(function() use ($request) {

            $data = $request->only('firstname', 'lastname', 'username', 'email', 'password');

    		$email = $this->createAccountEmail($data['email']);

	    	$this->assertUniqueUsername($data['username']);

	    	$account = Account::forceCreate([
				'username' => $data['username'],
				'email' => $email->id,
				'fullname' => $this->normalizeAccountName($data['firstname'], $data['lastname']),
				'creator' => Account::whereCreator(true)->count() == 0,
				'created' => Carbon::now()->toDateTimeString()
	    	]);

	    	$this->setAccountPassword($account, $data['password']);
    		
    		return $account->fresh();

    	});
    }

    public function changeName(Request $request)
    {
    	$account = $this->getAuthenticatedAccount();
    	$names = $request->only('firstname', 'lastname');

    	$account->fullname = $this->normalizeAccountName($names['firstname'], $names['lastname']);
    	$account->save();

    	return $account;
    }

    public function changeUsername(Request $request)
    {
    	$account = $this->getAuthenticatedAccount();
    	$username = $request->input('username');
    	
    	if (Str::lower($username) !== Str::lower($account->username)) {
    		$this->assertUniqueUsername($username);
    	}

    	$account->username = $username;
    	$account->save();
    	
    	return $account;
    }

    public function changePassword(Request $request)
    {
    	$account = $this->getAuthenticatedAccount();
    	$password = $request->only('old', 'new');

    	return $this->changeAccountPassword($account, $password['old'], $password['new']);
    }

    public function activateAccount(Request $request, Account $account)
    {
    	if ($account->activated) {
    		throw new \Exception('Account is already activated.');
    	}

    	$account->activated = Carbon::now()->toDateTimeString();
    	$account->save();

    	return $account;
    }

    public function disableAccount(Request $request, Account $account)
    {
    	if ($account->disabled instanceof Carbon) {
    		throw new \Exception('Account is already disabled.');
    	}

		// logout account from all active sessions
		$account->disabled = Carbon::now()->toDateTimeString();
		$account->save();

    	return $account;
    }

    public function enableAccount(Request $request, Account $account)
    {
    	if (! is_null($account->disabled)) {
	    	$account->disabled = null;
	    	$account->save();
	    }

    	return $account;
    }
}
