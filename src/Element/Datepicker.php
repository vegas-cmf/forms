<?php
/**
 * This file is part of Vegas package
 *
 * @author Arkadiusz Ostrycharz <aostrycharz@amsterdam-standard.pl>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage https://github.com/vegas-cmf
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Vegas\Forms\Element;

use Phalcon\Forms\Element\Text;
use Vegas\Forms\DecoratedTrait;
use Vegas\Forms\Decorator;

class Datepicker extends Text
{
    use DecoratedTrait;

    public function __construct($name, $attributes = null)
    {
        $this->addFilter('dateToTimestamp');
        $templatePath = implode(DIRECTORY_SEPARATOR, [dirname(__FILE__), 'Datepicker', 'views', '']);
        $this->setDecorator(new Decorator($templatePath));
        parent::__construct($name, $attributes);
    }

    public function getValue()
    {
        $value = parent::getValue();

        if ($value && is_numeric($value)) {
            $dateTime = new \DateTime();
            $dateTime->setTimestamp(parent::getValue());

            $value = $dateTime->format('Y-m-d');
        }
        
        return $value;
    }
}
