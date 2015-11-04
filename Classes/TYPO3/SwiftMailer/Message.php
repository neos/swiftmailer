<?php
namespace TYPO3\SwiftMailer;

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

/**
 * Message class for the SwiftMailer package
 *
 * @Flow\Scope("prototype")
 */
class Message extends \Swift_Message {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\SwiftMailer\MailerInterface
	 */
	protected $mailer;

	/**
	 * True if the message has been sent.
	 * @var boolean
	 */
	protected $sent = FALSE;

	/**
	 * Holds the failed recipients after the message has been sent
	 * @var array
	 */
	protected $failedRecipients = array();

	/**
	 * Sends the message.
	 *
	 * @return integer the number of recipients who were accepted for delivery
	 */
	public function send() {
		$this->sent = TRUE;
		$this->failedRecipients = array();
		return $this->mailer->send($this, $this->failedRecipients);
	}

	/**
	 * Checks whether the message has been sent.
	 *
	 * @return boolean
	 */
	public function isSent() {
		return $this->sent;
	}

	/**
	 * Returns the recipients for which the mail was not accepted for delivery.
	 *
	 * @return array the recipients who were not accepted for delivery
	 */
	public function getFailedRecipients() {
		return $this->failedRecipients;
	}

}
