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
use Phalcon\Forms\Element;
use Phalcon\Forms\ElementInterface;
use Vegas\Forms\Decorator;
use Vegas\Forms\Decorator\DecoratedTrait;

class RadioGroup extends Element implements ElementInterface
{
    use DecoratedTrait {
        renderDecorated as private baseRenderDecorated;
    }

    protected $elements;

    final public function __construct($name, $attributes = null)
    {
        parent::__construct($name, $attributes);
        $templatePath = implode(DIRECTORY_SEPARATOR, [dirname(__FILE__), 'RadioGroup', 'views', '']);
        $this->setDecorator(new Decorator($templatePath));
        $this->getDecorator()->setDI(Di::getDefault());
        $this->getDecorator()->setTemplateName('jquery');
    }

    public function render($attributes = null)
    {
        return $this->renderDecorated($attributes);
    }

    public function setElements(array $elements)
    {
        $this->elements = $elements;
    }

    public function getElements()
    {
        $value = $this->getForm()->getValue($this->getName());
        foreach ($this->elements as $element) {
            if ($element->getName() == $value) {
                $element->setDefault($value);
            }
        }
        return $this->elements;
    }

    /**
     * Render element decorated with specific view/template.
     *
     * @param array|null $attributes
     * @return string
     * @throws \Vegas\Forms\Decorator\Exception\ElementNotDecoratedException
     */
    public function renderDecorated($attributes = null)
    {
        return $this->baseRenderDecorated($attributes);
    }
}
