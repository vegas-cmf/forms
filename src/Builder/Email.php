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
    Phalcon\Forms\Element\Text,
    Vegas\Validation\Validator\PresenceOf,
    Vegas\Validation\Validator\Email as EmailValidator;

trait Email
{
    
    /**
     * @param \Vegas\Forms\InputSettings $settings
     * @return \Phalcon\Forms\Element\Text
     */
    public function buildEmail(InputSettings $settings)
    {
        $name = $settings->getValue(InputSettings::IDENTIFIER_PARAM) ? $settings->getValue(InputSettings::IDENTIFIER_PARAM) : 'Email-'.mt_rand();
        $element = new Text($name);
        
        $element->addValidator(new EmailValidator);
        $settings->getValue(InputSettings::REQUIRED_PARAM) && $element->addValidator(new PresenceOf());
        $settings->getValue(InputSettings::LABEL_PARAM) && $element->setLabel($settings->getValue(InputSettings::LABEL_PARAM));
        $settings->getValue(InputSettings::DEFAULTS_PARAM) && $element->setDefault($settings->getValue(InputSettings::DEFAULTS_PARAM));
        $settings->getValue(InputSettings::PLACEHOLDER_PARAM) && $element->setAttribute('placeholder', $settings->getValue(InputSettings::PLACEHOLDER_PARAM));
        
        return $element;
    }
    
}
