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
     * @throws Exception
     * @throws \ReflectionException
     */
    public function create(string $transportType, array $transportOptions = [], array $transportConstructorArguments = null): \Swift_Transport
    {
        if (!class_exists($transportType)) {
            throw new Exception(sprintf('The specified transport backend "%s" does not exist.', $transportType), 1269351207);
        }

        if (is_array($transportConstructorArguments)) {
            $class = new \ReflectionClass($transportType);
            $transport = $class->newInstanceArgs($transportConstructorArguments);
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
