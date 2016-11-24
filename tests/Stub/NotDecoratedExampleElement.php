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
namespace Vegas\Tests\Stub;

use Phalcon\Forms\Element;
use Vegas\Forms\Decorator\DecoratedTrait;


class NotDecoratedExampleElement extends Element
{
    use DecoratedTrait;

    /**
     * @return mixed
     */
    public function getDecorator()
    {
        return $this->decorator;
    }

    /**
     * @param mixed $decorator
     */
    public function setDecorator($decorator)
    {
        $this->decorator = $decorator;
    }



    public function render($attributes = null) {
        return $this->renderDecorated($attributes);
    }
}
