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
 * @homepage https://github.com/vegas-cmf
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Vegas\Forms\Element;

use Vegas\Forms\Element\Cloneable\Exception\BaseElementNotSetException,
    Vegas\Forms\Element\Cloneable\Exception\CantInheritCloneableException,
    Vegas\Forms\Element\Cloneable\Validation\Extender As ValidationExtender,
    Phalcon\Forms\Element;

class Cloneable extends Element
{
    private $assets;
    private $baseElements = array();
    private $rows = array();
    private $currentRowIndex = 0;

    final public function __construct($name, $attributes = null)
    {
        parent::__construct($name, $attributes);
        $this->addValidator(new ValidationExtender(array('cloneable' => $this)));
    }

    public function setBaseElements(array $elements)
    {
        foreach ($elements as $element) {
            $this->addBaseElement($element);
        }

        return $this;
    }

    public function addBaseElement(\Phalcon\Forms\ElementInterface $element)
    {
        $this->baseElements[$element->getName()] = $element;

        return $this;
    }

    public function getBaseElements()
    {
        return $this->baseElements;
    }

    public function getBaseElement($name)
    {
        if (empty($this->baseElements[$name])) {
            return null;
        }

        return $this->baseElements[$name];
    }

    public function getRowIndex()
    {
        return $this->currentRowIndex;
    }

    public function render($attributes = null)
    {
        if (is_array($attributes)) {
            $attributes = array_merge($attributes, $this->getAttributes());
        } else {
            $attributes = $this->getAttributes();
        }

        $renderer = new Cloneable\Renderer($this, $attributes);
        return $renderer->run();
    }

    /*private function addAssets()
    {
        if ($this->getUserOption('sortable',false)) {
            $this->assets->addJs('assets/vendor/html5sortable/jquery.sortable.js');

        }
        $this->assets->addCss('assets/css/common/cloneable.css');
        $this->assets->addJs('assets/js/lib/vegas/ui/cloneable.js');
    }*/

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

    public function getRows()
    {
        if (empty($this->rows)) {
            $this->generateRows();
        }

        return $this->rows;
    }

    private function generateRows()
    {
        $this->validate();
        //$this->addAssets();

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

    private function addRow($values = null)
    {
        $row = new Cloneable\Row($this);

        if ($values !== null) {
            $row->setValues($values);
        }

        $this->rows[] = $row;
    }
}
