<?php

namespace App\Http\Controllers\Api\v1;

use App\Repositories\EventRepository;
use App\Http\Controllers\Controller;
use App\Http\Requests\EventStore;
use App\Actions\SendResponse;
use Illuminate\Http\Request;

class EventController extends Controller
{
	/**
	 * Get data events
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @param \App\Repositories\EventRepository
	 * @return \App\Actions\SendResponse
	 */
	public function index(EventRepository $eventRepository)
	{
		$per_page = isset(request()->perPage) && request()->perPage != ''
					? request()->perPage
					: 10;
		$eventRepository->getDataEvents($per_page);
		return SendResponse::acceptData($eventRepository->getEvents());
	}

	/**
	 * Get data event public
	 *
	 * @author shellrean <wandinak17@gamil.com>
	 * @param \App\Repositories\EventRepository
	 * @return \App\Actions\SendResponse
	 */
	public function public_event(EventRepository $eventRepository)
	{
		$eventRepository->getDataEventsComingSoon();
		return SendResponse::acceptData($eventRepository->getEvents());
	}

	/**
	 * Create data event
	 *
	 * @author shellrean <wandinak17@gamil.com>
	 * @param \App\Repositories\EventRepository
	 * @param \App\Http\Requests\EventStore
	 * @return \App\Actions\SendResponse
	 */
	public function store(EventStore $request, EventRepository $eventRepository)
	{
		$user = request()->user('api');
		$request->user_id = $user->id;
		$eventRepository->createNewEvent($request);
		return SendResponse::acceptData($eventRepository->getEvent());
	}

	/** 
	 * Show data event
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @param \App\Repositories\EventRepository
	 * @param $event_id
	 * @return \App\Actions\SendResponse
	 */
	public function show($event_id, EventRepository $eventRepository)
	{
		$eventRepository->getDataEvent($event_id);
		return SendResponse::acceptData($eventRepository->getEvent());
	}

	/**
	 * Update data event
	 *
	 * @author shellrean <wandinak17@gamil.com>
	 * @param \App\Repositories\EventRepository
	 * @param $event_id
	 * @return \App\Actions\SendResponse
	 */
	public function update($event_id, EventStore $request, EventRepository $eventRepository)
	{
		$eventRepository->updateDataEvent($request, $event_id);
		return SendResponse::acceptData('event updated');
	}

	/**
	 * Delete data event
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @param \App\Repositories\EventRepository
	 * @param $event_id
	 * @return \App\Actions\SendResponse
	 */
	public function destroy($event_id, EventRepository $eventRepository)
	{
		$eventRepository->deleteDataEvent($event_id);
		return SendResponse::acceptData('event deleted');
	}
}
