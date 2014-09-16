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

namespace Vegas\Forms\Builder;

use Vegas\Forms\BuilderAbstract;
use Vegas\Forms\InputSettings,
    Vegas\Forms\Element\Select as SelectInput,
    Vegas\Validation\Validator\PresenceOf,
    Vegas\Validation\Validator\InclusionIn;

/**
 * Class Select
 * @package Vegas\Forms\Builder
 */
class Select extends BuilderAbstract
{
    public function setElement()
    {
        $name = $this->settings->getValue(InputSettings::IDENTIFIER_PARAM) ? $this->settings->getValue(InputSettings::IDENTIFIER_PARAM) : get_class($this) . self::NAME_SEPARATOR . mt_rand();
        $this->element = new SelectInput($name);
    }

    public function setValidator()
    {
        if ($this->settings->getValue(InputSettings::REQUIRED_PARAM)) {
            $this->element->addValidator(new PresenceOf());
        } else {
            $this->element->setOptions(array(null => '---'));
        }
    }

    public function setData()
    {
        $data = $this->settings->getDataFromProvider();
        $this->element->addOptions($data);
        $this->element->addValidator(new InclusionIn(array('domain' => array_keys($this->element->getOptions()))));
    }
}
