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
namespace Vegas\Forms\Decorator;

use Vegas\Forms\Decorator\DecoratorInterface;
use Vegas\Forms\Decorator\Exception\ElementNotDecoratedException;

/**
 * Class UnDecoratedTrait - trait with renderDecorated for elements that don't need decorator (like
 * base Phalcon\Forms\Elements extended by our classes).
 *
 * @package Vegas\Forms
 */
trait UnDecoratedTrait
{
    public function renderDecorated($attributes = null)
    {
        return $this->render($attributes);
    }
}
