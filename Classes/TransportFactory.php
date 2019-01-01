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

use Neos\Utility\ObjectAccess;

/**
 * Transport factory for the SwiftMailer package
 */
class TransportFactory
{
    /**
     * Factory method which creates the specified transport with the given options.
     *
     * @param string $transportType Object name of the transport to create
     * @param array $transportOptions Options for the transport
     * @param array $transportArguments Constructor arguments for the transport
     * @return \Swift_Transport The created transport instance
     * @throws Exception
     * @throws \ReflectionException
     */
    public function create(string $transportType, array $transportOptions = [], array $transportArguments = null): \Swift_Transport
    {
        if (!class_exists($transportType)) {
            throw new Exception(sprintf('The specified transport backend "%s" does not exist.', $transportType), 1269351207);
        }

        if (is_array($transportArguments)) {
            $class = new \ReflectionClass($transportType);
            $transport = $class->newInstanceArgs($transportArguments);
        } else {
            $transport = new $transportType();
        }

        if ($transport instanceof \Swift_Transport) {
            foreach ($transportOptions as $optionName => $optionValue) {
                if (ObjectAccess::isPropertySettable($transport, $optionName)) {
                    ObjectAccess::setProperty($transport, $optionName, $optionValue);
                }
            }

            return $transport;
        }

        throw new Exception(sprintf('The specified transport backend "%s" does not implement %s.', $transportType, \Swift_Transport::class), 1544727431);
    }
}
