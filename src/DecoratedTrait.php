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
namespace Vegas\Forms;

use Phalcon\DiInterface;
use Vegas\Forms\Decorator\DecoratorInterface;
use Vegas\Forms\Decorator\Exception\ElementNotDecoratedException;

trait DecoratedTrait
{
    protected $decorator;

    public function render($attributes = null)
    {
        if (!($this->decorator instanceof DecoratorInterface)) {
            throw new ElementNotDecoratedException();
        }

        if (is_array($attributes)) {
            $attributes = array_merge($attributes, $this->getAttributes());
        } else {
            $attributes = $this->getAttributes();
        }

        $attributes['name'] = $this->getName();
        $attributes['value'] = $this->getValue();

        return $this->decorator->render($attributes);
    }

    public function getDecorator()
    {
        return $this->decorator;
    }

    public function setDecorator(DecoratorInterface $decorator)
    {
        $this->decorator = $decorator;
        return $this;
    }

    public function setDecoratorDi(DiInterface $di)
    {
        $this->decorator->setDI($di);
        return $this;
    }
}
