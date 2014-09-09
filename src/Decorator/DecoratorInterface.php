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

use Phalcon\DiInterface;

interface DecoratorInterface
{
    public function render($attributes = array());
    public function setTemplateName($name);
    public function setTemplatePath($path);
    public function setDI(DiInterface $di);
    public function getDI();
}
