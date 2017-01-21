<?php

namespace App\Http\Controllers\Core;

use Auth;
use App\Models\Account;
use App\Models\Invitation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InvitationController extends Controller
{
    protected function getAuthenticatedAccount()
    {
    	return Account::find(10);
    	return Auth::user();
    }

	public function getAccountInvitations(Request $request)
	{
		$account = $this->getAuthenticatedAccount();
		return $account->invitations;
	}

	public function invitePeople(Request $request)
	{
		$account = $this->getAuthenticatedAccount();
		// invite people
	}

	public function getAllInvitations(Request $request)
	{
		return Invitation::all();
	}

	public function acceptInvitation(Request $request, Invitation $invite)
	{}

	public function rejectInvitation(Request $request, Invitation $invite)
	{}
}
