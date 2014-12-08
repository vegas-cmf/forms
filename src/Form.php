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
    public function bind($data, $entity, $whitelist = null)
    {
        parent::bind($data, $entity, $whitelist);

        $this->bindArrays($data, $entity, $whitelist);
    }

    private function bindArrays($data, $entity, $whitelist = null)
    {
        $rawNames = array();

        foreach ($this->getElements() As $element) {
            $rawNames[] = preg_replace('/\[[a-zA-Z0-9\-\_]*\]/','', $element->getName());
        }

        foreach ($data As $name => $values) {
            if (!is_array($values) || !in_array($name, $rawNames)) {
                continue;
            }

            if (empty($whitelist) || isset($whitelist[$name])) {
                $nameArray = array($name);

                $values = $this->prepareValues($nameArray, $values);
                $entity->$name = $this->reindex($values);
            }
        }
    }

    /**
     * Adds an element to the form, if element has DecoratedInterface, injects DI
     *
     * @param \Phalcon\Forms\ElementInterface $element
     * @param string $postion
     * @param bool $type If $type is TRUE, the element wile add before $postion, else is after
     * @return \Phalcon\Forms\Form
     */
    public function add($element, $postion = null, $type = null)
    {
        if ($element instanceof Decorator\DecoratedInterface
            && $element->getDecorator() instanceof DecoratorInterface) {
            $element->getDecorator()->setDI($this->di);
        }

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
        } elseif ($this->has($name[0]) && $this->get($name[0]) instanceof \Vegas\Forms\Element\Cloneable) {
            $value = $this->prepareCloneableValue($name, $value);
        }

        if ($this->passArray($value) || $this->passScalar($value)) {
            return $value;
        }

        return null;
    }

    private function prepareCloneableValue(array $name, $value)
    {
        $cloneable = $this->get($name[0]);
        $elements = $cloneable->getBaseElements();

        if (isset($elements[$name[count($name)-1]])) {
            $element = $elements[$name[count($name)-1]];

            $filters = $element->getFilters();

            if (!empty($filters)) {
                foreach ($filters As $filter) {
                    $value = $this->getDI()->get('filter')->sanitize($value, $filter);
                }
            }
        }

        return $value;
    }

    private function passArray($value)
    {
        return is_array($value) && count($value);
    }

    private function passScalar($value)
    {
        return is_scalar($value) && (string)$value !== '';
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
        $unBracketName = str_replace(']', '', $name);
        $matches = explode('[',$unBracketName);

        if (count($matches)) {
            return $this->getArrayValue($matches);
        }

        return parent::getValue($name);
    }

    private function getArrayValue(array $matches)
    {
        $baseName = $matches[0];
        $value = parent::getValue($baseName);

        foreach ($matches As $key => $match) {
            if ($key) {
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
