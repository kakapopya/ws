<?php
namespace Lambq\Ws\Contracts;

interface ListenerInterface
{
	/**
	 * Fire an event
	 * See all events on https://github.com/WHAnonymous/Chat-API/wiki/Ws-Documentation#list-of-all-events
	 *
	 * @param  string $eventFired Event name
	 * @param  array  $parameters Event parameters
	 * @param  string $message    Message resumed about event
	 * @return void
	 */
	public function fire($eventFired, array $parameters, $message);
}
