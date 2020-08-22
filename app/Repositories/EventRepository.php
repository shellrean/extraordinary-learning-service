<?php

namespace App\Repositories;

use App\Event;

class EventRepository
{
	/**
	 * Data events
	 * @var App\Event;
	 */
	private $evets;

	/**
	 * Data event
	 * @var App\Event
	 */
	private $event;

	/**
	 * Retreive data events
	 *
	 * @author shellrean <wandinak17@gamil.com>
	 * @since 1.0.0
	 * @return Collection
	 */
	public function getEvents()
	{
		return $this->events;
	}

	/**
	 * Retreive data event
	 *
	 * @author shellrean <wandinak17@gamil.com>
	 * @since 1.0.0
	 * @return App\Event
	 */
	public function getEvent()
	{
		return $this->event;
	}

	/**
	 * Set data event
	 *
	 * @author shellrean <wandinak17@gamil.com>
	 * @since 1.0.0
	 * @param $event
	 * @return void
	 */
	public function setEvent($event)
	{
		$this->event = $event;
	}

	/** 
	 * Get data events
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param $per_page
	 * @return void
	 */
	public function getDataEvents(int $per_page)
	{
		try {
			$events = Event::orderBy('id', 'desc');
			$this->events = $events->paginate($per_page);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Get data comming soons
	 *
	 * @author shellran <wandinak17@gamil.com>
	 * @since 1.0.0
	 * @return void
	 */
	public function getDataEventsComingSoon()
	{
		try {
			$events = Event::where('date', '>=', date('Y-m-d'))->orderBy('date')->take(10);
			$this->events = $events->get();
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Get data event
	 *
	 * @author shellrean <wandinak17@gamil.com>
	 * @since 1.0.0
	 * @param $event_id
	 * @return void
	 */
	public function getDataEvent($event_id, bool $exception = true)
	{
		try {
			$event = Event::find($event_id);
			if(!$event && $exception) {
				throw new \App\Exceptions\ModelNotFoundException('event not found');
			}
			$this->setEvent($event);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Create data event
	 *
	 * @author shellrean <wandinak17@gamil.com>
	 * @since 1.0.0
	 * @param \App\Http\Request\EventStore
	 * @return void
	 */
	public function createNewEvent($request)
	{
		try {
			$data = [
				'user_id'	=> $request->user_id,
				'location'	=> $request->location,
				'title'		=> $request->title,
				'body'		=> $request->body,
				'date'		=> $request->date,
				'time'		=> $request->time,
				'settings'	=> $request->settings
			];
			$event = Event::create($data);
			$this->setEvent($event);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Update data event
	 *
	 * @author shellrean <wandinak17@gamil.com>
	 * @since 1.0.0
	 * @param \App\Http\Request\EventStore
	 * @return void
	 */
	public function updateDataEvent($request, $event_id = '')
	{
		try {
			if($event_id != '') {
				$this->getDataEvent($event_id);
			}
			$data = [
				'location'	=> $request->location,
				'title'		=> $request->title,
				'body'		=> $request->body,
				'date'		=> $request->date,
				'time'		=> $request->time,
				'settings'	=> $request->settings
			];
			$this->event->update($data);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Delete data event
	 *
	 * @author shellrean <wandinak17@gamil.com>
	 * @since 1.0.0
	 * @param \App\Http\Requests\EventStore
	 * @return void
	 */
	public function deleteDataEvent($event_id)
	{
		try {
			Event::where('id', $event_id)->delete();
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}
}