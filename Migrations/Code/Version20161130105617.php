<?php

namespace Neos\Flow\Core\Migrations;

/*
 * This file is part of the Neos.Flow package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

/**
 * Adjusts code to package renaming from "TYPO3.SwiftMailer" to "Neos.SwiftMailer".
 */
class Version20161130105617 extends AbstractMigration
{
    public function getIdentifier()
    {
        return 'Neos.SwiftMailer-20161130105617';
    }

    /**
     * @return void
     */
    public function up()
    {
        $this->searchAndReplace('TYPO3\SwiftMailer', 'Neos\SwiftMailer');
        $this->searchAndReplace('TYPO3.SwiftMailer', 'Neos.SwiftMailer');

        $this->moveSettingsPaths('TYPO3.SwiftMailer', 'Neos.SwiftMailer');
    }
}
