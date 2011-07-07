<?php
namespace TYPO3\SwiftMailer\Transport;

/*                                                                        *
 * This script belongs to the FLOW3 package "SwiftMailer".                *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * A logging swift transport for functional tests and development. It stores and logs sent messages.
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License', version 3 or later
 */
class LoggingTransport implements \TYPO3\SwiftMailer\TransportInterface {

	/**
	 * Store sent messages for testing
	 *
	 * @var array
	 */
	static protected $deliveredMessages = array();

	/**
	 * @inject
	 * @var \TYPO3\FLOW3\Log\SystemLoggerInterface
	 */
	protected $systemLogger;

	/**
	 * The logging transport is always started
	 *
	 * @return boolean Always TRUE for this transport
	 */
	public function isStarted() {
		return TRUE;
	}

	/**
	 * No op
	 *
	 * @return void
	 * @author Christopher Hlubek <hlubek@networkteam.com>
	 */
	public function start() {}

	/**
	 * No op
	 *
	 * @return void
	 * @author Christopher Hlubek <hlubek@networkteam.com>
	 */
	public function stop() {}

	/**
	 * "Send" the given Message. This transport will add it to a stored collection of sent messages
	 * for testing purposes and log the message to the system logger.
	 *
	 * @param \Swift_Mime_Message $message The message to send
	 * @param array &$failedRecipients Failed recipients
	 * @return int
	 * @author Christopher Hlubek <hlubek@networkteam.com>
	 */
	public function send(\Swift_Mime_Message $message, &$failedRecipients = NULL) {
		$count = count((array)$message->getTo()) + count((array)$message->getCc()) + count((array)$message->getBcc());

		self::$deliveredMessages[] = $message;

		$this->systemLogger->log('Sent email to ' . $message->getTo(), LOG_DEBUG, array(
			'message' => $message->toString()
		));

		return $count;
	}

	/**
	 * No op
	 *
	 * @param \Swift_Events_EventListener $plugin
	 * @return void
	 * @author Christopher Hlubek <hlubek@networkteam.com>
	 */
	public function registerPlugin(\Swift_Events_EventListener $plugin) {}

	/**
	 * Get delivered messages that were sent through this transport
	 *
	 * @return array
	 * @author Christopher Hlubek <hlubek@networkteam.com>
	 */
	static public function getDeliveredMessages() {
		return self::$deliveredMessages;
	}

	/**
	 * Reset the delivered messages (e.g. for tearDown in functional test)
	 *
	 * @return void
	 * @author Christopher Hlubek <hlubek@networkteam.com>
	 */
	static public function reset() {
		self::$deliveredMessages = array();
	}

}
?>