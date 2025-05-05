<?php

declare(strict_types=1);

namespace Neos\SwiftMailer\Transport;

/*
 * This file is part of the Neos.SwiftMailer package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\SwiftMailer\TransportInterface;

/**
 * A swift transport that delivers to a text file according to RFC 4155.
 *
 * Originally written by Ernesto Baschny <ernst@cron-it.de>
 */
class MboxTransport implements TransportInterface
{
    /**
     * The file to write the mails into
     */
    protected string $mboxPathAndFilename;

    /**
     * Set path and filename of mbox file to use.
     */
    public function setMboxPathAndFilename(string $mboxPathAndFilename): void
    {
        $this->mboxPathAndFilename = $mboxPathAndFilename;
    }

    /**
     * The mbox transport is always started
     */
    public function isStarted(): bool
    {
        return true;
    }

    /**
     * No op
     */
    public function start(): void
    {
    }

    /**
     * No op
     */
    public function stop(): void
    {
    }

    /**
     * Outputs the mail to a text file according to RFC 4155.
     */
    public function send(\Swift_Mime_SimpleMessage $message, &$failedRecipients = null): int
    {
        $message->generateId();

        // Create a mbox-like header
        $mboxFrom = $this->getReversePath($message);
        $mboxDate = strftime('%c', $message->getDate()->getTimestamp());
        $messageString = sprintf('From %s %s', $mboxFrom, $mboxDate) . chr(10);

        // Add the complete mail inclusive headers
        $messageString .= $message->toString();
        $messageString .= chr(10) . chr(10);

        // Write the mbox file
        file_put_contents($this->mboxPathAndFilename, $messageString, FILE_APPEND | LOCK_EX);

        // Return every recipient as "delivered"
        return count((array)$message->getTo()) + count((array)$message->getCc()) + count((array)$message->getBcc());
    }

    /**
     * Determine the best-use reverse path for this message
     */
    private function getReversePath(\Swift_Mime_SimpleMessage $message): string
    {
        $returnPath = $message->getReturnPath();
        $sender = $message->getSender();
        $from = $message->getFrom();
        $path = '';
        if (!empty($returnPath)) {
            $path = $returnPath;
        } elseif (!empty($sender)) {
            $path = $sender;
        } elseif (!empty($from)) {
            if (is_array($from)) {
                $keys = array_keys($from);
                $path = array_shift($keys);
            } else {
                $path = $from;
            }
        }
        return $path;
    }

    /**
     * No op
     */
    public function registerPlugin(\Swift_Events_EventListener $plugin): void
    {
    }

    /**
     * This Transport mechanism is always alive.
     */
    public function ping(): bool
    {
        return true;
    }
}
