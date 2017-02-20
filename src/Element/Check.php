<?php
/**
 * This file is part of Vegas package
 *
 * @author Arkadiusz Ostrycharz <aostrycharz@amsterdam-standard.pl>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage http://vegas-cmf.github.io/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Vegas\Forms\Element;

use Phalcon\Di;
use Vegas\Forms\Decorator;
use Vegas\Forms\Decorator\DecoratedTrait;

class Check extends \Phalcon\Forms\Element\Check
{
    use DecoratedTrait;

    public function __construct($name, $attributes = null)
    {
        $di = Di::getDefault();
        $templatePath = implode(DIRECTORY_SEPARATOR, [dirname(__FILE__), 'Check', 'views', '']);
        $this->setDecorator(new Decorator($templatePath));
        $this->getDecorator()->setDI($di);
        $this->getDecorator()->setTemplateName('jquery');

        parent::__construct($name, $attributes);
    }

    public function render($attributes = null)
    {
        $this->setAttribute('checked', $this->getValue() || $this->getDefault() ? true : null);
        return parent::render($attributes);
    }
}
