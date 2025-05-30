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

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Log\Utility\LogEnvironment;
use Neos\SwiftMailer\TransportInterface;
use Psr\Log\LoggerInterface;

/**
 * A logging swift transport for functional tests and development. It stores and logs sent messages.
 */
class LoggingTransport implements TransportInterface
{
    /**
     * Store sent messages for testing
     */
    protected static array $deliveredMessages = [];

    /**
     * @Flow\Inject
     */
    protected LoggerInterface $logger;

    /**
     * The logging transport is always started
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
     * "Send" the given Message. This transport will add it to a stored collection of sent messages
     * for testing purposes and log the message to the system logger.
     */
    public function send(\Swift_Mime_SimpleMessage $message, &$failedRecipients = null): int
    {
        self::$deliveredMessages[] = $message;

        $this->logger->debug(
            'Sent email to ' . $this->buildStringFromEmailAndNameArray($message->getTo()),
            array_merge(LogEnvironment::fromMethodName(__METHOD__), ['message' => $message->toString()])
        );

        return count((array)$message->getTo()) + count((array)$message->getCc()) + count((array)$message->getBcc());
    }

    /**
     * Builds a plaintext-compatible string representing an array of given E-Mail addresses.
     *
     * @param array $addresses The addresses where the key is the email address and the value is a name
     * @return string Looking like "John Doe <demo@example.org>, Jeanne Dough <foo@example.org>, somebody@example.org"
     */
    protected function buildStringFromEmailAndNameArray(array $addresses): string
    {
        $results = [];
        foreach ($addresses as $emailAddress => $name) {
            if ($name !== '') {
                $results[] = sprintf('%s <%s>', $name, $emailAddress);
            } else {
                $results[] = $emailAddress;
            }
        }
        return implode(', ', $results);
    }

    /**
     * No op
     */
    public function registerPlugin(\Swift_Events_EventListener $plugin): void
    {
    }

    /**
     * Get delivered messages that were sent through this transport
     */
    public static function getDeliveredMessages(): array
    {
        return self::$deliveredMessages;
    }

    /**
     * Reset the delivered messages (e.g. for tearDown in functional test)
     */
    public static function reset(): void
    {
        self::$deliveredMessages = [];
    }

    /**
     * This Transport mechanism is always alive.
     */
    public function ping(): bool
    {
        return true;
    }
}
