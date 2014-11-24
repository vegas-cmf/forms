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

use Phalcon\DI;
use Vegas\Forms\BuilderAbstract;
use Vegas\Forms\InputSettings,
    Vegas\Forms\Element\Datepicker as DatepickerInput;
use Vegas\Validation\Validator\Date;
use Vegas\Validation\Validator\Regex;

/**
 * Class Datepicker
 * @package Vegas\Forms\Builder
 */
class Datepicker extends BuilderAbstract
{
    public function setElement()
    {
        $name = $this->settings->getValue(InputSettings::IDENTIFIER_PARAM) ? $this->settings->getValue(InputSettings::IDENTIFIER_PARAM) : preg_replace('/.*\\\/', '', get_class($this)) . self::NAME_SEPARATOR . mt_rand();
        $this->element = (new DatepickerInput($name))
            ->setAssetsManager($this->settings->assets);
    }

    public function setValidator()
    {
        parent::setValidator();
        $this->element->addValidator(new Regex(['pattern' => '/[0-9]*/']));
    }

    public function setAdditionalOptions()
    {
        $format = new \Phalcon\Forms\Element\Text('format');
        $format->setLabel("Format");
        $this->additionalOptions[] = $format;

        DI::getDefault()->get('assets')->addJs('assets/js/lib/vegas/formbuilder/datepicker.js');
    }


}