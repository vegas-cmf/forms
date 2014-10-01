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

use Vegas\Forms\Decorator\DecoratorInterface;
use Vegas\Forms\Decorator\Exception\ElementNotDecoratedException;

trait DecoratedTrait
{
    /**
     * @var DecoratorInterface
     */
    protected $decorator;

    /**
     * Render element decorated with specific view/template.
     *
     * @param array|null $attributes
     * @return string
     * @throws Decorator\Exception\ElementNotDecoratedException
     */
    public function renderDecorated($attributes = null)
    {
        if (!($this->decorator instanceof DecoratorInterface)) {
            throw new ElementNotDecoratedException();
        }

        $customAttributes = is_array($attributes) ? $attributes : [];

        $baseAttributes = array_merge([
                'id'    => $this->getName(),
                'name'  => $this->getName()
            ],
            $customAttributes,
            $this->getAttributes()
        );

        if (isset($baseAttributes['value'])) {
            $value = $baseAttributes['value'];
            unset($baseAttributes['value']);
        } else {
            $value = $this->getValue();
        }

        return $this->decorator->render($this, $value, $baseAttributes);
    }

    /**
     * Get element decorator.
     *
     * @return DecoratorInterface
     */
    public function getDecorator()
    {
        return $this->decorator;
    }

    /**
     * Set decorator for element.
     *
     * @param DecoratorInterface $decorator
     * @return $this
     */
    public function setDecorator(DecoratorInterface $decorator)
    {
        $this->decorator = $decorator;
        return $this;
    }
}
