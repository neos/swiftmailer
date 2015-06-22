<?php
namespace TYPO3\SwiftMailer\Transport;

/*                                                                        *
 * This script belongs to the Flow package "SwiftMailer".                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\SwiftMailer\TransportInterface;

/**
 * A swift transport that delivers to a text file according to RFC 4155.
 *
 * Originally written by Ernesto Baschny <ernst@cron-it.de>
 */
class MboxTransport implements TransportInterface {

	/**
	 * @var string The file to write the mails into
	 */
	protected $mboxPathAndFilename;

	/**
	 * Set path and filename of mbox file to use.
	 *
	 * @param string $mboxPathAndFilename
	 */
	public function setMboxPathAndFilename($mboxPathAndFilename) {
		$this->mboxPathAndFilename = $mboxPathAndFilename;
	}

	/**
	 * The mbox transport is always started
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
	 */
	public function start() {}

	/**
	 * No op
	 *
	 * @return void
	 */
	public function stop() {}

	/**
	 * Outputs the mail to a text file according to RFC 4155.
	 *
	 * @param \Swift_Mime_Message $message The message to send
	 * @param array &$failedRecipients Failed recipients (no failures in this transport)
	 * @return integer
	 */
	public function send(\Swift_Mime_Message $message, &$failedRecipients = NULL) {
		$message->generateId();

			// Create a mbox-like header
		$mboxFrom = $this->getReversePath($message);
		$mboxDate = strftime('%c', $message->getDate());
		$messageString = sprintf('From %s  %s', $mboxFrom, $mboxDate) . chr(10);

			// Add the complete mail inclusive headers
		$messageString .= $message->toString();
		$messageString .= chr(10) . chr(10);

			// Write the mbox file
		file_put_contents($this->mboxPathAndFilename, $messageString, FILE_APPEND | LOCK_EX);

			// Return every receipient as "delivered"
		return count((array)$message->getTo()) + count((array)$message->getCc()) + count((array)$message->getBcc());
	}

	/**
	 * Determine the best-use reverse path for this message
	 *
	 * @param \Swift_Mime_Message $message
	 * @return mixed|NULL
	 */
	private function getReversePath(\Swift_Mime_Message $message) {
		$returnPath = $message->getReturnPath();
		$sender = $message->getSender();
		$from = $message->getFrom();
		$path = NULL;
		if (!empty($returnPath)) {
			$path = $returnPath;
		} elseif (!empty($sender)) {
			$keys = array_keys($sender);
			$path = array_shift($keys);
		} elseif (!empty($from)) {
			$keys = array_keys($from);
			$path = array_shift($keys);
		}
		return $path;
	}

	/**
	 * No op
	 *
	 * @param \Swift_Events_EventListener $plugin
	 * @return void
	 */
	public function registerPlugin(\Swift_Events_EventListener $plugin) {}

}
