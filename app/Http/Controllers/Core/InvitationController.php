<?php

namespace App\Http\Controllers\Core;

use DB;
use Auth;
use Carbon;
use Keygen;
use Exception;
use App\Models\Account;
use App\Models\Invitation;
use Illuminate\Http\Request;
use App\Events\PersonInvited;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\Support\ArrayInspector;

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
		return DB::transaction(function() use ($request) {

			$account = $this->getAuthenticatedAccount();
			$input = $request->input();

			if (ArrayInspector::isAssoc($input)) {
				$input = [$input];
			} elseif (! ArrayInspector::isIndexed($input)) {
				throw new Exception('Malformed request data.');
			}

			foreach ($input as $data) {

				$data = collect($data)->only('recipient', 'fullname')->toArray();

				// validate data

				$data['from'] = $account->id;
				$data['code'] = Keygen::numeric(6)->prefix(mt_rand(1, 9))->generate();
				$data['created'] = Carbon::now()->toDateTimeString();

				$invitation = Invitation::forceCreate($data);

				if ($invitation) {
					event(new PersonInvited($invitation));
				}
			}
			
			return $account->invitations;
		});
	}

	public function getAllInvitations(Request $request)
	{
		return Invitation::all();
	}

	protected function followInvitationRequest(Request $request, $token, $accept = true)
	{
		return Invitation::unguarded(function() use ($token, $accept) {
			
			$accept = !is_bool($accept) ?: $accept;
			$invitation = Invitation::getInvitationFromToken($token);

			if ($invitation->followed->isPast()) {
				throw new Exception('Invitation has been followed already.');
			}

			$invitation->getAllInvitationsForRecipient()->each(function ($iv, $accept) {

				$iv->update([
					'followed' => Carbon::now()->toDateTimeString(),
					'status' => $accept ? 'accepted' : 'rejected',
				]);

				// fire a broadcast event
			});

			return $invitation;
		});
	}

	public function acceptInvitation(Request $request, $token)
	{
		return $this->followInvitationRequest($request, $token, true);
	}

	public function rejectInvitation(Request $request, $token)
	{
		return $this->followInvitationRequest($request, $token, false);
	}
}
