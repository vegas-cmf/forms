<?php
/**
 * This file is part of Vegas package
 *
 * @author Radosław Fąfara <radek@archdevil.pl>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage https://github.com/vegas-cmf
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Vegas\Forms\Builder;

use Vegas\Forms\InputSettings,
    Vegas\Forms\Element\Datepicker as DatepickerInput,
    Vegas\Validation\Validator\PresenceOf;

trait Datepicker
{
    /**
     * @param \Vegas\Forms\InputSettings $settings
     * @return \Phalcon\Forms\Element\Text
     */
    public function buildDatepicker(InputSettings $settings)
    {
        $name = $settings->getValue(InputSettings::IDENTIFIER_PARAM) ? $settings->getValue(InputSettings::IDENTIFIER_PARAM) : 'Datepicker-'.mt_rand();
        $element = new DatepickerInput($name);
        
        $settings->getValue(InputSettings::REQUIRED_PARAM) && $element->addValidator(new PresenceOf());
        $settings->getValue(InputSettings::LABEL_PARAM) && $element->setLabel($settings->getValue(InputSettings::LABEL_PARAM));
        $settings->getValue(InputSettings::DEFAULTS_PARAM) && $element->setDefault($settings->getValue(InputSettings::DEFAULTS_PARAM));
        $settings->getValue(InputSettings::PLACEHOLDER_PARAM) && $element->setAttribute('placeholder', $settings->getValue(InputSettings::PLACEHOLDER_PARAM));
        
        return $element;
    }
}
