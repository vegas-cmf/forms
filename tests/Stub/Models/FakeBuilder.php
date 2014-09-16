<?php
/**
 * This file is part of Vegas package
 *
 * @author Mateusz Aniołek <dev@mateusz-aniolek.com>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage https://bitbucket.org/amsdard/vegas-phalcon
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vegas\Tests\Stub\Models;

use Vegas\Forms\BuilderAbstract;
use \Phalcon\Forms\Element\Text;
use Vegas\Forms\InputSettings;

class FakeBuilder extends BuilderAbstract
{
    function setElement()
    {
        $name = $this->settings->getValue(InputSettings::IDENTIFIER_PARAM) ? $this->settings->getValue(InputSettings::IDENTIFIER_PARAM) : get_class($this). self::NAME_SEPARATOR . mt_rand();
        $this->element = new Text($name);
    }
}
