<?php namespace Cviebrock\Guzzle\Plugin;


use Guzzle\Common\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


/**
 * Strips BOM from request body if it exists.  Helps with JSON/XML decoding of .NET service responses.
 */
class StripBomPlugin implements EventSubscriberInterface
{

	public static function getSubscribedEvents()
	{
		return array(
			'request.complete' => 'onRequestComplete',
		);
	}

	/**
	 * When the request is complete, check the message body and strip any BOMs, if they exist.
	 *
	 * @param Event $event
	 */
	public function onRequestComplete(Event $event)
	{
		if ($body = $event['response']->getBody()) {
			if (substr($body, 0, 3) === "\xef\xbb\xbf") {
				// UTF-8
				$event['response']->setBody(substr($body, 3));
			} else if (substr($body, 0, 4) === "\xff\xfe\x00\x00" ||
					   substr($body, 0, 4) === "\x00\x00\xfe\xff"
			) {
				// UTF-32
				$event['response']->setBody(substr($body, 4));
			} else if (substr($body, 0, 2) === "\xff\xfe" ||
					   substr($body, 0, 2) === "\xfe\xff"
			) {
				// UTF-16
				$event['response']->setBody(substr($body, 2));
			}
		}
	}

}
