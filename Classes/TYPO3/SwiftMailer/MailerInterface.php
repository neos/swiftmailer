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

/**
 * Mailer interface for the SwiftMailer package
 */
interface MailerInterface {

	/**
	 * Send the given Message like it would be sent in a mail client.
	 *
	 * All recipients (with the exception of Bcc) will be able to see the other
	 * recipients this message was sent to.
	 *
	 * Recipient/sender data will be retrieved from the Message object.
	 *
	 * The return value is the number of recipients who were accepted for
	 * delivery.
	 *
	 * @param \Swift_Mime_Message $message
	 * @param array $failedRecipients An array of failures by-reference
	 * @return int
	 */
	public function send(\Swift_Mime_Message $message, &$failedRecipients = null);

	/**
	 * Register a plugin using a known unique key (e.g. myPlugin).
	 *
	 * @param \Swift_Events_EventListener $plugin
	 */
	public function registerPlugin(\Swift_Events_EventListener $plugin);

	/**
	 * The Transport used to send messages.
	 *
	 * @return \Swift_Transport
	 */
	public function getTransport();

}
