<?php

namespace App\Enhance\Routing\Indexes;

class ApiRoutesIndex extends AbstractRoutesIndex
{
	public function loadRoutes()
	{
		$R = $this->router;

		$R->group(['prefix' => 'http/rest', 'namespace' => 'Core'], function() use ($R) {

			$R->group(['prefix' => 'account'], function() use ($R) {

				$R->get('/', 'AccountController@getAccount');

				$R->post('/name/change', 'AccountController@changeName');
				$R->post('/username/change', 'AccountController@changeUsername');
				$R->post('/password/change', 'AccountController@changePassword');
				$R->post('/settings/change', 'AccountController@changeSettings');

				$R->get('/invitations', 'InvitationController@getAccountInvitations');
				$R->post('/invitations', 'InvitationController@invitePeople');
			});

			$R->group(['prefix' => 'accounts'], function() use ($R) {

				$R->post('/', 'AccountController@createAccount');

				$R->post('/{account}/activate', 'AccountController@activateAccount');
				$R->post('/{account}/enable', 'AccountController@enableAccount');
				$R->post('/{account}/disable', 'AccountController@disableAccount');
				
				$R->post('/{account}/permissions/change', 'AccountController@changePermissions');
			});

			$R->group(['prefix' => 'event'], function() use ($R) {

				$R->get('/labels', 'EventController@getLabels');
				$R->post('/labels', 'EventController@createLabel');

				$R->get('/services', 'EventController@getServices');
				$R->post('/services', 'EventController@createService');

				$R->get('/attendees', 'PeopleController@getAllAttendees');
				$R->post('/attendees', 'PeopleController@createNewAttendee');

				$R->get('/attendees/{attendee}', 'PeopleController@getAttendee');
				$R->post('/attendees/{attendee}/attend', 'PeopleController@attendEvent');

				$R->group(['prefix' => 'locations'], function() use ($R) {

					$R->get('/', 'LocationController@getAllLocations');
					$R->post('/', 'LocationController@createLocation');

					$R->get('/{location}', 'LocationController@getLocation');
					$R->put('/{location}', 'LocationController@modifyLocation');
					$R->post('/{location}/events', 'LocationController@attachEventToLocation');

					$R->get('/{location}/photos', 'LocationController@getLocationPhotos');
					$R->post('/{location}/photos', 'LocationController@uploadLocationPhoto');
				});

				$R->group(['prefix' => 'invitations'], function() use ($R) {

					$R->get('/', 'InvitationController@getAllInvitations');
					$R->post('/{iv_token}/accept', 'InvitationController@acceptInvitation');
					$R->post('/{iv_token}/reject', 'InvitationController@rejectInvitation');
				});

				$R->group(['prefix' => 'couple'], function() use ($R) {

					$R->get('/', 'PeopleController@getBothCouple');
					$R->get('/{couple}', 'PeopleController@getOneCouple');
					$R->put('/{couple}', 'PeopleController@modifyOneCouple');

					$R->get('/{couple}/photos', 'PeopleController@getCouplePhotos');
					$R->post('/{couple}/photos', 'PeopleController@uploadCouplePhoto');
				});

				$R->group(['prefix' => 'people'], function() use ($R) {

					$R->get('/', 'PeopleController@getPeople');
					$R->get('/{person}', 'PeopleController@getPerson');
					$R->put('/{person}', 'PeopleController@modifyPerson');

					$R->get('/{person}/labels', 'PeopleController@getPersonLabels');
					$R->post('/{person}/labels', 'PeopleController@createPersonLabel');
				});
			});

			$R->group(['prefix' => 'events'], function() use ($R) {

				$R->get('/', 'EventController@getAllEvents');
				$R->post('/', 'EventController@createEvent');

				$R->get('/{event}', 'EventController@getEvent');
				$R->put('/{event}', 'EventController@modifyEvent');

				$R->get('/{event}/crew', 'EventController@getEventCrew');
				$R->get('/{event}/people', 'EventController@getEventPeople');
				$R->get('/{event}/attending', 'EventController@getAllAttending');
			});

		});
	}
}
