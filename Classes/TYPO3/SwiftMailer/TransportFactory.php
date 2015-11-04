<?php
namespace TYPO3\SwiftMailer;

/*                                                                        *
 * This script belongs to the Flow package "SwiftMailer".                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Reflection\ObjectAccess;

/**
 * Transport factory for the SwiftMailer package
 */
class TransportFactory {

	/**
	 * Factory method which creates the specified transport with the given options.
	 *
	 * @param string $transportType Object name of the transport to create
	 * @param array $transportOptions Options for the transport
	 * @param array $transportArguments Constructor arguments for the transport
	 * @return \TYPO3\SwiftMailer\TransportInterface The created transport instance
	 * @throws Exception
	 */
	public function create($transportType, array $transportOptions = array(), array $transportArguments = NULL) {
		if (!class_exists($transportType)) {
			throw new Exception('The specified transport backend "' . $transportType . '" does not exist.', 1269351207);
		}

		if (is_array($transportArguments)) {
			$class = new \ReflectionClass($transportType);
			$transport =  $class->newInstanceArgs($transportArguments);
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
