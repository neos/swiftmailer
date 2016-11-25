<?php
namespace Neos\SwiftMailer;

/*
 * This file is part of the Neos.SwiftMailer package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\Flow\Annotations as Flow;

/**
 * Message class for the SwiftMailer package
 *
 * @Flow\Scope("prototype")
 */
class Message extends \Swift_Message
{

    /**
     * @Flow\Inject
     * @var \Neos\SwiftMailer\MailerInterface
     */
    protected $mailer;

    /**
     * True if the message has been sent.
     * @var boolean
     */
    protected $sent = false;

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
    public function send()
    {
        $this->sent = true;
        $this->failedRecipients = array();
        return $this->mailer->send($this, $this->failedRecipients);
    }

    /**
     * Checks whether the message has been sent.
     *
     * @return boolean
     */
    public function isSent()
    {
        return $this->sent;
    }

    /**
     * Returns the recipients for which the mail was not accepted for delivery.
     *
     * @return array the recipients who were not accepted for delivery
     */
    public function getFailedRecipients()
    {
        return $this->failedRecipients;
    }

}
