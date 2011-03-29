<?php
declare(ENCODING = 'utf-8');
namespace F3\SwiftMailer;

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

require_once(__DIR__ . '/../Resources/Private/PHP/SwiftMailer/swift_required.php');

/**
 * Message class for the SwiftMailer package
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License', version 3 or later
 * @scope prototype
 */
class Message extends \Swift_Message {

	/**
	 * @inject
	 * @var \F3\SwiftMailer\MailerInterface
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
	 * @author Karsten Dambekalns <karsten@typo3.org>
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
	 * @author Karsten Dambekalns <karsten@typo3.org>
	 */
	public function isSent() {
		return $this->sent;
	}

	/**
	 * Returns the recipients for which the mail was not accepted for delivery.
	 *
	 * @return array the recipients who were not accepted for delivery
	 * @author Karsten Dambekalns <karsten@typo3.org>
	 */
	public function getFailedRecipients() {
		return $this->failedRecipients;
	}

}

?>