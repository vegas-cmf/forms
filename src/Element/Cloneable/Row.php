<?php
/**
 * This file is part of Vegas package
 * 
 * Cloneable element is representation of dynamic data set.
 * 
 * @author Arkadiusz Ostrycharz <aostrycharz@amsterdam-standard.pl>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage https://github.com/vegas-cmf
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Vegas\Forms\Element\Cloneable;

use Vegas\Forms\Element\Cloneable,
    Phalcon\Forms\ElementInterface;

class Row
{
    private $elements = array();
    private $cloneable;
    private $rowIndex;
    
    public function __construct(Cloneable $cloneable)
    {
        $this->cloneable = $cloneable;
        $this->rowIndex = $cloneable->getRowIndex();
        
        foreach ($this->cloneable->getBaseElements() As $element) {
            $field = clone $element;
            $field->setName($this->getSingleFieldName($element));
            $this->set($element->getName(), $field);
        }
    }

    public function get($elementName)
    {
        return $this->elements[$elementName];
    }
    
    public function set($baseName, ElementInterface $element)
    {
        $this->elements[$baseName] = $element;
    }
    
    private function getSingleFieldName(ElementInterface $element)
    {
        if (count($this->cloneable->getBaseElements()) > 1) {
            return $this->cloneable->getName().'['.$this->rowIndex.']['.$element->getName().']';
        }
        return $this->cloneable->getName().'[]';
    }
    
    public function setValues($values)
    {
        foreach ($this->elements As $baseName => $element) {
            $element->setDefault($this->getSingleFieldValue($baseName, $values));
        }
        
        return $this;
    }
    
    private function getSingleFieldValue($baseName, $value)
    {
        if (count($this->cloneable->getBaseElements()) > 1) {
            return $this->getArrayedValue($baseName, $value);
        }
        return $value;
    }
    
    private function getArrayedValue($baseName, $value)
    {
        if (!isset($value[$baseName])) {
            return null;
        }
        
        $returnValue = $value[$baseName];
        
        $filters = $this->elements[$baseName]->getFilters();

        if (!empty($filters)) {
            foreach ($filters As $filter) {
                $returnValue = $this->cloneable->getForm()
                    ->getDI()->get('filter')->sanitize($returnValue, $filter->getOption('filter'));
            }
        }
        
        return $returnValue;
    }
    
    public function getElements()
    {
        return $this->elements;
    }
}
