<?php
/**
 * This file is part of Vegas package
 *
 * Cloneable element is representation of dynamic data set.
 * Example usage:
 * <code>
 * // always set the base element!
 * $answers = new Cloneable('answers');
 * $answers->setBaseElements(array(
 *     new Text('field1'),
 *     new Text('field2')
 * );
 * // and/or
 * $answers->addBaseElement(new Text(''));
 * $answers->setLabel($this->i18n->_('Answers'));
 * $answers->addValidator(new SizeOf(array('min' => 2, 'max' => 6)));
 * $this->add($answers);
 * </code>
 *
 * @author Arkadiusz Ostrycharz <aostrycharz@amsterdam-standard.pl>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage http://vegas-cmf.github.io/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Vegas\Forms\Element;

use Vegas\Forms\Decorator;
use Vegas\Forms\Element\Cloneable\Exception\BaseElementNotSetException;
use Vegas\Forms\Element\Cloneable\Exception\CantInheritCloneableException;
use Vegas\Forms\Element\Cloneable\Exception\RenderDecoratedOnlyException;
use Vegas\Forms\Element\Cloneable\Validation\Extender As ValidationExtender;
use Phalcon\Forms\Element;

class Cloneable extends Element implements Decorator\DecoratedInterface
{
    use Decorator\DecoratedTrait {
        renderDecorated as private baseRenderDecorated;
    }

    private $baseElements = array();
    private $rows = array();
    private $currentRowIndex = 0;

    /**
     * Final Cloneable field constructor with Decorator and ValidationExtender adding.
     *
     * @param string $name
     * @param null $attributes
     */
    final public function __construct($name, $attributes = null)
    {
        parent::__construct($name, $attributes);
        $templatePath = implode(DIRECTORY_SEPARATOR, [dirname(__FILE__), 'Cloneable', 'views', '']);
        $this->setDecorator(new Decorator($templatePath));
        $this->getDecorator()->setTemplateName('jquery');
        $this->addValidator(new ValidationExtender(array('cloneable' => $this)));
    }

    /**
     * Set base elements for Cloneable Row.
     *
     * @param array $elements
     * @return $this
     */
    public function setBaseElements(array $elements)
    {
        foreach ($elements as $element) {
            $this->addBaseElement($element);
        }

        return $this;
    }

    /**
     * Add one element to base elements array.
     *
     * @param \Phalcon\Forms\ElementInterface $element
     * @return $this
     */
    public function addBaseElement(\Phalcon\Forms\ElementInterface $element)
    {
        $this->baseElements[$element->getName()] = $element;

        return $this;
    }

    /**
     * Get all base elements for Cloneable Row.
     *
     * @return array
     */
    public function getBaseElements()
    {
        return $this->baseElements;
    }

    /**
     * Get base element by name.
     *
     * @param $name
     * @return null
     */
    public function getBaseElement($name)
    {
        if (empty($this->baseElements[$name])) {
            return null;
        }

        return $this->baseElements[$name];
    }

    /**
     * Returns current used row index.
     *
     * @return int
     */
    public function getRowIndex()
    {
        return $this->currentRowIndex;
    }

    /**
     * Cloneable element uses decorator with jQuery template by default.
     *
     * @param null $attributes
     * @return string
     */
    public function render($attributes = null)
    {
        return $this->renderDecorated($attributes);
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
        $this->validate();
        return $this->baseRenderDecorated($attributes);
    }

    /**
     * Checking all base elements before generating rows.
     *
     * @throws Cloneable\Exception\BaseElementNotSetException
     * @throws Cloneable\Exception\CantInheritCloneableException
     */
    private function validate()
    {
        if (empty($this->baseElements)) {
            throw new BaseElementNotSetException();
        }

        foreach ($this->baseElements As $element) {
            if ($element instanceof Cloneable) {
                throw new CantInheritCloneableException();
            }
        }
    }

    /**
     * Returns all Cloneable Rows for given data.
     * The getRows() method will also try to generate them if they are not set.
     *
     * @return array
     */
    public function getRows()
    {
        if (empty($this->rows)) {
            $this->generateRows();
        }

        return $this->rows;
    }

    /**
     * Generating rows for given data.
     */
    private function generateRows()
    {
        $this->rows = array();

        // empty row for cloneable js
        $this->addRow();

        $values = $this->getForm()->getValue($this->getName());

        if (is_array($values) && count($values)) {
            foreach ($values As $key => $rowValues) {
                $this->currentRowIndex = $key;
                $this->addRow($rowValues);
            }
        } else {
            $this->addRow();
        }
    }

    /**
     * Adds one Cloneable\Row object to rows array.
     *
     * @param null $values
     */
    private function addRow($values = null)
    {
        $row = new Cloneable\Row($this);

        if ($values !== null) {
            $row->setValues($values);
        }

        $this->rows[] = $row;
    }
}
