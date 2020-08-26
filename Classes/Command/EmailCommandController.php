<?php
declare(strict_types=1);

namespace Neos\SwiftMailer\Command;

/*
 * This file is part of the Neos.SwiftMailer package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\Flow\Cli\CommandController;
use Neos\SwiftMailer\Message;

class EmailCommandController extends CommandController
{
    /**
     * A command for creating and sending simple emails.
     *
     * @param string $from The from address of the message
     * @param string $to The to address of the message
     * @param string $subject The subject of the message
     * @param string $body The body of the message
     * @param string $contentType The body content type of the message (Default: test/plain)
     * @param string $charset The body charset of the message (Default: UTF8)
     */
    public function sendCommand(string $from, string $to, string $subject, string $body = '', string $contentType = 'text/plain', $charset = 'UTF8'): void
    {
        $message = (new Message())
            ->setFrom($from)
            ->setTo($to)
            ->setSubject($subject)
            ->setBody($body)
            ->setContentType($contentType)
            ->setCharset($charset);

        $sentMessages = $message->send();

        $this->outputLine(sprintf('<success>%s emails were successfully sent.</success>', $sentMessages));
    }
}
