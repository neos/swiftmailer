<?php
declare(ENCODING = 'utf-8');
namespace F3\SwiftMailer;

/*                                                                        *
 * This script belongs to the FLOW3 package "SwiftMailer".                *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

require_once(__DIR__ . '/../Resources/Private/PHP/SwiftMailer/swift_required.php');

/**
 * Transport factory for the SwiftMailer package
 *
 * @version $Id$
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License', version 3 or later
 */
class TransportFactory {

	/**
	 * Factory method which creates the specified transport with the given options.
	 *
	 * @param string $backend Object name of the transport to create
	 * @param array $backendOptions Options for the backend
	 * @return \F3\SwiftMailer\TransportInterface The created transport instance
	 * @author Karsten Dambekalns <karsten@typo3.org>
	 */
	public function create($backend, array $backendOptions = array()) {
		if (!class_exists($backend)) throw new \F3\SwiftMailer\Exception('The specified transport backend "' . $backend . '" does not exist.', 1269351207);
		$transport = new $backend();

		foreach ($backendOptions as $optionName => $optionValue) {
			$setterName = 'set' . ucfirst($optionName);
			$transport->$setterName($optionValue);
		}

		return $transport;
	}

}

?>