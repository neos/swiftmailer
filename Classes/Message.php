<?php

declare(strict_types=1);

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
     *
     * @var bool
     */
    protected $sent = false;

    /**
     * Holds the failed recipients after the message has been sent
     *
     * @var array
     */
    protected $failedRecipients = [];

    /**
     * Sends the message.
     *
     * @return int the number of recipients who were accepted for delivery
     */
    public function send(): int
    {
        $this->sent = true;
        $this->failedRecipients = [];
        return $this->mailer->send($this, $this->failedRecipients);
    }

    /**
     * Checks whether the message has been sent.
     *
     * @return bool
     */
    public function isSent(): bool
    {
        return $this->sent;
    }

    /**
     * Returns the recipients for which the mail was not accepted for delivery.
     *
     * @return array the recipients who were not accepted for delivery
     */
    public function getFailedRecipients(): array
    {
        return $this->failedRecipients;
    }
}
