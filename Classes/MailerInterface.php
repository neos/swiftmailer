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

/**
 * Mailer interface for the SwiftMailer package
 */
interface MailerInterface
{
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
     */
    public function send(\Swift_Mime_SimpleMessage $message, array &$failedRecipients = null);

    /**
     * Register a plugin using a known unique key (e.g. myPlugin).
     */
    public function registerPlugin(\Swift_Events_EventListener $plugin);

    /**
     * The Transport used to send messages.
     */
    public function getTransport();
}
