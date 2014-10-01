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
namespace Vegas\Forms\Decorator;

use Phalcon\DI\InjectionAwareInterface,
    Phalcon\Forms\ElementInterface;

interface DecoratorInterface extends InjectionAwareInterface
{
    public function render(ElementInterface $formElement, $value = '', $attributes = array());
    public function setTemplateName($name);
    public function setTemplatePath($path);
}
