<?php
/**
 * This file is part of Vegas package
 *
 * @author Radosław Fąfara <radek@archdevil.pl>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage https://bitbucket.org/amsdard/vegas-phalcon
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Vegas\Forms\Builder;

use Vegas\Forms\InputSettings,
    Vegas\Forms\Element\Select as SelectInput,
    Phalcon\Validation\Validator\PresenceOf,
    Phalcon\Validation\Validator\InclusionIn;

trait Select
{
    
    /**
     * @param \Vegas\Forms\InputSettings $settings
     * @return \Vegas\Forms\Element\Select
     */
    public function buildSelect(InputSettings $settings)
    {
        $name = $settings->getValue(InputSettings::IDENTIFIER_PARAM) ? $settings->getValue(InputSettings::IDENTIFIER_PARAM) : 'Select-'.mt_rand();
        $element = new SelectInput($name);
        
        if ($settings->getValue(InputSettings::REQUIRED_PARAM)) {
            $element->addValidator(new PresenceOf());
        } else {
            $element->setOptions(array(null => '---'));
        }
        $settings->getValue(InputSettings::LABEL_PARAM) && $element->setLabel($settings->getValue(InputSettings::LABEL_PARAM));
        $settings->getValue(InputSettings::DEFAULTS_PARAM) && $element->setDefault($settings->getValue(InputSettings::DEFAULTS_PARAM));
        $settings->getValue(InputSettings::PLACEHOLDER_PARAM) && $element->setAttribute('placeholder', $settings->getValue(InputSettings::PLACEHOLDER_PARAM));
        
        if ($settings->getValue(InputSettings::DATA_PARAM)) {
            $data = $settings->getDataFromProvider();
            $element->addOptions($data);
            $element->addValidator(new InclusionIn(array('domain' => array_keys($element->getOptions()))));
        }
        
        return $element;
    }
    
}
