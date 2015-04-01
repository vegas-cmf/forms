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

use Vegas\Forms\DecoratorInterface;

interface DecoratedInterface
{
    /**
     * Render element decorated with specific view/template.
     *
     * @param array|null $attributes
     * @return string
     * @throws \Vegas\Forms\Decorator\Exception\ElementNotDecoratedException
     */
    public function renderDecorated($attributes = null);

    /**
     * Get element decorator.
     *
     * @return \Vegas\Forms\DecoratorInterface
     */
    public function getDecorator();

    /**
     * Set decorator for element.
     *
     * @param DecoratorInterface $decorator
     * @return $this
     */
    public function setDecorator(DecoratorInterface $decorator);
}
