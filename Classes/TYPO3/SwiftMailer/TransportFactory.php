<?php
namespace TYPO3\SwiftMailer;

/*                                                                        *
 * This script belongs to the FLOW3 package "SwiftMailer".                *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * Transport factory for the SwiftMailer package
 */
class TransportFactory {

	/**
	 * Factory method which creates the specified transport with the given options.
	 *
	 * @param string $backend Object name of the transport to create
	 * @param array $backendOptions Options for the backend
	 * @return \TYPO3\SwiftMailer\TransportInterface The created transport instance
	 */
	public function create($backend, array $backendOptions = array()) {
		if (!class_exists($backend)) {
			throw new \TYPO3\SwiftMailer\Exception('The specified transport backend "' . $backend . '" does not exist.', 1269351207);
		}
		$transport = new $backend();

		foreach ($backendOptions as $optionName => $optionValue) {
			if (\TYPO3\Flow\Reflection\ObjectAccess::isPropertySettable($transport, $optionName)) {
				\TYPO3\Flow\Reflection\ObjectAccess::setProperty($transport, $optionName, $optionValue);
			}
		}

		return $transport;
	}

}
?>