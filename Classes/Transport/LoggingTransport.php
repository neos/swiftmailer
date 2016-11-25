<?php
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
use Neos\SwiftMailer\TransportInterface;

/**
 * A logging swift transport for functional tests and development. It stores and logs sent messages.
 */
class LoggingTransport implements TransportInterface
{

    /**
     * Store sent messages for testing
     *
     * @var array
     */
    static protected $deliveredMessages = array();

    /**
     * @Flow\Inject
     * @var \Neos\Flow\Log\SystemLoggerInterface
     */
    protected $systemLogger;

    /**
     * The logging transport is always started
     *
     * @return boolean Always TRUE for this transport
     */
    public function isStarted()
    {
        return true;
    }

    /**
     * No op
     *
     * @return void
     */
    public function start()
    {
    }

    /**
     * No op
     *
     * @return void
     */
    public function stop()
    {
    }

    /**
     * "Send" the given Message. This transport will add it to a stored collection of sent messages
     * for testing purposes and log the message to the system logger.
     *
     * @param \Swift_Mime_Message $message The message to send
     * @param array &$failedRecipients Failed recipients
     * @return integer
     */
    public function send(\Swift_Mime_Message $message, &$failedRecipients = null)
    {
        self::$deliveredMessages[] = $message;

        $this->systemLogger->log('Sent email to ' . $this->buildStringFromEmailAndNameArray($message->getTo()), LOG_DEBUG, array(
            'message' => $message->toString()
        ));

        return count((array)$message->getTo()) + count((array)$message->getCc()) + count((array)$message->getBcc());
    }

    /**
     * Builds a plaintext-compatible string representing an array of given E-Mail addresses.
     *
     * @param array $addresses The addresses where the key is the email address and the value is a name
     * @return string Looking like "John Doe <demo@example.org>, Jeanne Dough <foo@example.org>, somebody@example.org"
     */
    protected function buildStringFromEmailAndNameArray(array $addresses)
    {
        $results = array();
        foreach ($addresses as $emailAddress => $name) {
            if (strlen($name) > 0) {
                $results[] = sprintf('%s <%s>', $name, $emailAddress);
            } else {
                $results[] = $emailAddress;
            }
        }
        return implode(', ', $results);
    }

    /**
     * No op
     *
     * @param \Swift_Events_EventListener $plugin
     * @return void
     */
    public function registerPlugin(\Swift_Events_EventListener $plugin)
    {
    }

    /**
     * Get delivered messages that were sent through this transport
     *
     * @return array
     */
    static public function getDeliveredMessages()
    {
        return self::$deliveredMessages;
    }

    /**
     * Reset the delivered messages (e.g. for tearDown in functional test)
     *
     * @return void
     */
    static public function reset()
    {
        self::$deliveredMessages = array();
    }

}
