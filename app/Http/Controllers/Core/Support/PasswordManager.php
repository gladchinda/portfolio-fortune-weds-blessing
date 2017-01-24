<?php

namespace App\Http\Controllers\Core\Support;

use DB;
use Hash;
use Carbon;
use App\Models\Salt;
use App\Models\Secret;
use App\Models\Account;
use App\Models\Password;
use Illuminate\Support\Str;
use App\Enhance\Exceptions\OldAccountPasswordException;
use App\Enhance\Exceptions\WrongAccountPasswordException;

trait PasswordManager
{
	protected function generateSaltedPasswordHash($password, $salt = null)
	{
		$md5 = [];

		$withSalt = is_string($salt) && !empty($salt);
		$pswdlength = strlen($password);
		$saltlength = $withSalt ? strlen($salt) : 20;

		$pswdlengthBitOne = $pswdlength & 1;

		$md5[] = md5(substr(str_rot13($password), round($pswdlength / 3)) . substr($password, 0, -(floor($pswdlength / 4))));

		if ($withSalt) {
			$md5[] = md5(base64_encode($salt));
			array_unshift($md5, md5(substr($salt, floor($pswdlength / 3), $saltlength - $pswdlength)));
		}

		for ($i = 0, $count = count($md5); $i < $count; $i++) {
			$md5[$i] = (($i % 2) ^ $pswdlengthBitOne) ? strrev($md5[$i]) : $md5[$i];
		}

        return strtoupper(md5(base64_encode(join('::', $md5))));
	}

	protected function hashPasswordForAccount(Account $account, $password, $salt = null)
	{
		$hash = $this->generateSaltedPasswordHash($password, $salt);

		return base64_encode(sprintf("%s::%s:%s", $account->id, $hash, Carbon::parse($account->created)->getTimestamp()));
	}

    protected function computeAccountPasswordHash(Account $account, $password, $salt = null)
    {
        return Hash::make($this->hashPasswordForAccount($account, $password, $salt));
    }

    protected function verifyAccountPasswordHash(Account $account, $hash, $password, $salt = null)
    {
        return Hash::check($this->hashPasswordForAccount($account, $password, $salt), $hash);
    }

    protected function setAccountPassword(Account $account, $password)
    {
        return DB::transaction(function() use ($account, $password) {

            $hashes = ['secret', 'history'];

            for ($i = 0, $h = count($hashes); $i < $h; $i++) {
                $salt = Str::random(60);
                $hashes[ $hashes[$i] ] = [
                    'salt' => $salt,
                    'hash' => $this->computeAccountPasswordHash($account, $password, $salt),
                ];
                unset($hashes[$i]);
            }

    		$passwordSalt = Salt::unguarded(function() use ($account, $hashes) {
                return Salt::updateOrCreate(['account' => $account->id], ['salt' => $hashes['secret']['salt']]);
            });

    		$secret = Secret::unguarded(function() use ($account, $hashes) {
                return Secret::updateOrCreate(['account' => $account->id], ['hash' => $hashes['secret']['hash']]);
            });

    		$password = Password::forceCreate([
    			'account' => $account->id,
    			'salt' => $hashes['history']['salt'],
    			'hash' => $hashes['history']['hash'],
    			'created' => Carbon::now()->toDateTimeString()
    		]);

    		return $account->fresh();
    	});
    }

    protected function changeAccountPassword(Account $account, $oldPassword, $newPassword)
    {
    	if (! $this->isAccountPassword($account, $oldPassword)) {
    		throw new WrongAccountPasswordException($account);
    	}

    	if ($this->isOldAccountPassword($account, $newPassword)) {
    		throw new OldAccountPasswordException($account);
    	}

    	return $this->setAccountPassword($account, $newPassword);
    }

    protected function isAccountPassword(Account $account, $password)
    {
    	$salt = $account->passwordSalt->salt;
    	$hash = $account->secret->hash;

    	return $this->verifyAccountPasswordHash($account, $hash, $password, $salt);
    }

    protected function isOldAccountPassword(Account $account, $password)
    {
    	foreach ($account->passwords as $old) {
    		if ($this->verifyAccountPasswordHash($account, $old->hash, $password, $old->salt)) {
    			return true;
    		}
    	}

    	return false;
    }
}
