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

class Form extends \Phalcon\Forms\Form
{
    /**
     * Bind also an array values.
     * 
     * @param array $data
     * @param object $entity
     * @param array $whitelist
     */
    public function bind($data, $entity, $whitelist=null)
    {
        parent::bind($data, $entity, $whitelist);
        
        $this->bindArrays($data, $entity);
    }
    
    private function bindArrays($data, $entity)
    {
        foreach ($data As $name => $values) {
            if (is_array($values)) {
                $nameArray = array($name);

                $values = $this->prepareValues($nameArray, $values);

                $entity->$name = $this->reindex($values);
            }
        }
    }

    /**
     * Adds an element to the form
     *
     * @param \Phalcon\Forms\ElementInterface $element
     * @param string $postion
     * @param bool $type If $type is TRUE, the element wile add before $postion, else is after
     * @return \Phalcon\Forms\Form
     */
    public function add($element, $postion = null, $type = null)
    {
        $reflectionClass = new \ReflectionClass($element);
        $elementType = strtolower(str_replace($reflectionClass->getNamespaceName() . '\\', '', $reflectionClass->getName()));
        $element->setUserOption('_type', $elementType);
        // hax even when $postion and $type are null by default, call parent::add($element, $postion, $type)
        // causes exception : Array position does not exist
        if (!is_null($postion) || !is_null($type)) {
            return parent::add($element, $postion, $type);
        } else {
            return parent::add($element);
        }
    }

    /**
     * Remove empty strings from array.
     * 
     * @param array $values
     * @return array
     */
    private function prepareValues(array $name, array $values)
    {
        $tempArray = array();
        
        foreach ($values As $key => $value) { 
            $baseName = $name;
            $baseName[] = $key;
            
            $value = $this->prepareValue($baseName, $value);
            
            if ($value !== null) {
                $tempArray[$key] = $value;
            }
        }
        
        return $tempArray;
    }
    
    private function prepareValue(array $name, $value)
    {
        if (is_array($value)) {
            $value = $this->prepareValues($name, $value);
        }
        
        if ($this->passArray($value) || $this->passString($value)) {
            return $value;
        }
        
        return null;
    }
    
    private function passArray($value)
    {
        return is_array($value) && count($value);
    }
    
    private function passString($value)
    {
        return !is_array($value) && $value !== '';
    }

    private function reindex($values)
    {
        foreach ($values As $key => $value) {
            if (!is_numeric($key)) {
                return $values;
            }
        }

        return array_values($values);
    }

    /**
     * Return single value or value from array (based on $name).
     * @param string $name
     * @return mixed
     */
    public function getValue($name)
    {
        $matches = array();

        if (preg_match('/^([a-zA-Z0-9\-\_]+)\[([a-zA-Z0-9\-\_]*)\](\[([a-zA-Z0-9\-\_]*)\])?$/', $name, $matches)) {
            return $this->getArrayValue($matches);
        }

        return parent::getValue($name);
    }

    private function getArrayValue(array $matches)
    {
        $baseName = $matches[1];
        $value = parent::getValue($baseName);

        foreach ($matches As $key => $match) {
            if ($key !== 0 && $key%2 === 0) {
                if (isset($value[$match])) {
                    $value = $value[$match];
                } else {
                    return null;
                }
            }
        }

        return $value;
    }
}
