<?php
/**
 * This file is part of Vegas package
 *
 * @author Radosław Fąfara <radek@archdevil.pl>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage https://bitbucket.org/amsdard/vegas-phalcon
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Vegas\Forms;

use Vegas\Forms\DataProvider\Exception\NotFoundException,
    Vegas\Forms\DataProvider\DataProviderInterface,
    Vegas\Forms\Element\Select,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Check,
    Vegas\Validation\Validator\PresenceOf;

/**
 * Subform used to validate settings for each created input element in FormFactory
 */
class InputSettings extends \Vegas\Forms\Form
{
    
    /**
     * Param name used for element type recognition
     */
    const TYPE_PARAM = 'type';
    
    /**
     * Param name used for field identification - has to be unique
     */
    const IDENTIFIER_PARAM = 'name';
    
    /**
     * Param name used if a field should be required
     */
    const REQUIRED_PARAM = 'required';
    
    /**
     * Param name used for element label
     */
    const LABEL_PARAM = 'label';
    
    /**
     * Param name used for default value
     */
    const DEFAULTS_PARAM = 'defaults';
    
    /**
     * Param name used for placeholder value
     */
    const PLACEHOLDER_PARAM = 'placeholder';
    
    /**
     * Param name used for fully qualified classnames implementing DataProviderInterface
     */
    const DATA_PARAM = 'data';
    
    public function initialize()
    {
        $params = array(
            'useEmpty' => true,
            'emptyText' => $this->i18n->_('Please Select...'),
        );
        
        $type = new Select(self::TYPE_PARAM,null,$params);
        $type
                ->addOptions($this->di->get('formFactory')->getAvailableInputs())
                ->addValidator(new PresenceOf)
                ->setLabel('Input type');
        $this->add($type);
        
        $identifier = (new Text(self::IDENTIFIER_PARAM))
                ->addValidator(new PresenceOf)
                ->setLabel('Unique ID');
        $this->add($identifier);
        
        $required = (new Check(self::REQUIRED_PARAM))
                ->setAttribute('value', true)
                ->setLabel('Required field');
        $this->add($required);
        
        $label = (new Text(self::LABEL_PARAM))
                ->setLabel('Label');
        $this->add($label);
        
        $defaults = (new Text(self::DEFAULTS_PARAM))
                ->setLabel('Default value');
        $this->add($defaults);

        $placeholder = (new Text(self::PLACEHOLDER_PARAM))
                ->setLabel('Placeholder text');
        $this->add($placeholder);
        
        $this->addDataProviderInput();
    }
    
    /**
     * Proxy method to retrieve data to populate select lists.
     * @return array
     * @throws \Vegas\Forms\DataProvider\Exception\NotFoundException When DI is not configured properly or a wrong value is provided.
     */
    public function getDataFromProvider()
    {
        $select = $this->get(self::DATA_PARAM);
        $classname = $select->getValue();
        if (!class_exists($classname) || !array_key_exists($classname, $select->getOptions())) {
            throw new NotFoundException;
        }
        return (new $classname)->getData();
    }
    
    /**
     * Adds selectable list of data providers.
     * Usable only for selectable input types.
     */
    private function addDataProviderInput()
    {
        $input = (new Select(self::DATA_PARAM))
                ->setOptions(array(null => '---'));
        $dataProviderClasses = array();
        foreach ($this->di->get('config')->formFactory->dataProviders as $classname) {
            $provider = new $classname;
            if ($provider instanceof DataProviderInterface) {
                $dataProviderClasses[$classname] = $provider->getName();
            }
        }
        $input
                ->addOptions($dataProviderClasses)
                ->setLabel('Data provider');
        $this->add($input);
    }
}
