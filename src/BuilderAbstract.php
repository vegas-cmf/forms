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

namespace Vegas\Forms;

use Phalcon\DI;
use Vegas\Forms\Element\AssetsInjectableInterface;
use Vegas\Validation\Validator\PresenceOf;

/**
 * Class BuilderAbstract
 * @package Vegas\Forms
 */
abstract class BuilderAbstract implements BuilderInterface
{
    /**
     * Separator for default name element
     */
    const NAME_SEPARATOR = '-';

    /**
     * Currently built element
     * @var mixed
     */
    protected $element;

    /**
     * Stores fields of record retrieved from database
     * @var InputSettings
     */
    protected $settings;

    /**
     * Method for building form element
     * @param InputSettings $settings
     * @return mixed
     */
    public function build(InputSettings $settings)
    {
        $this->settings = $settings;

        $this->setElement();
        $this->setValidator();
        $this->setLabel();
        $this->setDefault();
        $this->setAttributes();
        $this->setData();

        return $this->getElement();
    }

    /**
     * Method sets element and return object instance. Only for form builder purpose.
     * @return mixed
     */
    public function initElement()
    {
        if(is_null($this->settings)) {
            $this->settings = new InputSettings();

        }

        return $this->build($this->settings);
    }

    /**
     * Default setter for dataProvider field
     */
    public function setData() {
        if ($this->settings->getValue(InputSettings::DATA_PARAM)) {
        }
    }

    /**
     * Default setter for element validator
     */
    public function setValidator()
    {
        if($this->settings->getValue(InputSettings::REQUIRED_PARAM)) {
            $this->element->addValidator(new PresenceOf());
        }
    }

    /**
     * Default setter for element label
     */
    public function setLabel()
    {
        if($this->settings->getValue(InputSettings::LABEL_PARAM)) {
            $this->element->setLabel($this->settings->getValue(InputSettings::LABEL_PARAM));
        } else {
            $this->element->setLabel(preg_replace('/.*\\\/', '', get_class($this)));
        }
    }

    /**
     * Default setter for element default value
     */
    public function setDefault()
    {
        if($this->settings->getValue(InputSettings::DEFAULTS_PARAM)) {
            $this->element->setDefault($this->settings->getValue(InputSettings::DEFAULTS_PARAM));
        }
    }

    /**
     * Default setter for placeholder attribute
     */
    public function setAttributes()
    {
        if($this->settings->getValue(InputSettings::PLACEHOLDER_PARAM)) {
            $this->element->setAttribute('placeholder',  $this->settings->getValue(InputSettings::PLACEHOLDER_PARAM));
        }
    }

    /**
     * Getter for element field
     * @return mixed
     */
    public function getElement()
    {
        return $this->element;
    }

} 