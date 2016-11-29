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
     * @return \Neos\SwiftMailer\TransportInterface The created transport instance
     * @throws Exception
     */
    public function create($transportType, array $transportOptions = array(), array $transportArguments = null)
    {
        if (!class_exists($transportType)) {
            throw new Exception('The specified transport backend "' . $transportType . '" does not exist.', 1269351207);
        }

        if (is_array($transportArguments)) {
            $class = new \ReflectionClass($transportType);
            $transport = $class->newInstanceArgs($transportArguments);
        } else {
            $transport = new $transportType();
        }

        foreach ($transportOptions as $optionName => $optionValue) {
            if (ObjectAccess::isPropertySettable($transport, $optionName)) {
                ObjectAccess::setProperty($transport, $optionName, $optionValue);
            }
        }

        return $transport;
    }

}
