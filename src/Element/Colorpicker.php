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

class Colorpicker extends Text
{
    use DecoratedTrait;

    public function __construct($name, $attributes = null)
    {
        $templatePath = implode(DIRECTORY_SEPARATOR, [dirname(__FILE__), 'Colorpicker', 'views', '']);
        $this->setDecorator(new Decorator($templatePath));
        parent::__construct($name, $attributes);
    }
}
