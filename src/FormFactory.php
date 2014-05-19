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

use Phalcon\DI\InjectionAwareInterface,
    Vegas\Forms\Exception\InvalidInputSettingsException,
    Vegas\Forms\Builder\Exception\NotFoundException,
    Vegas\Forms\Form as GenericForm,
    Vegas\Forms\InputSettings as InputSettingsForm;

class FormFactory implements InjectionAwareInterface
{
    use Builder\Datepicker,
        Builder\Email,
        Builder\RichTextArea,
        Builder\Select,
        Builder\Text;
    
    /**
     * Common prefix for all trait builder methods used by this factory
     */
    const TRAIT_METHOD_PREFIX = 'build';

    /**
     * @var \Phalcon\DiInterface $dependencyInjector
     */
    protected $di;

    /**
     * Sets the dependency injector
     *
     * @param \Phalcon\DiInterface $dependencyInjector
     * @return $this
     */
    public function setDI($dependencyInjector)
    {
        $this->di = $dependencyInjector;

        return $this;
    }

    /**
     * Returns the internal dependency injector
     *
     * @return \Phalcon\DiInterface
     */
    public function getDI()
    {
        return $this->di;
    }
    
    /**
     * Retrieves all implemented trait names (without leading namespace).
     * May be used e.x. when listing them in select list.
     * @return array Elements have the same key and value
     */
    public function getAvailableInputs()
    {
        $traits = preg_replace('/.*\\\/', '', class_uses($this));
        return array_combine($traits, $traits);
    }
    
    /**
     * Acts as factory pattern: generates form object with all dependent elements.
     * Each element should be represented by a specific trait.
     * Uses i18n for provided labels.
     * 
     * @param array $data each form element data
     * @return \Vegas\Forms\Form full instance of form
     * @throws \Vegas\Forms\Exception\InvalidInputSettingsException When provided invalid (manipulated) input
     */
    public function createForm(array $data)
    {
        $form = new GenericForm;
        foreach ($data as $item) {
            $settings = new InputSettingsForm;
            if (!$settings->isValid($item)) {
                throw new InvalidInputSettingsException;
            }
            $settings->bind($item, new \stdClass);
            $element = $this->callBuilderMethod($settings);
            if ($element->getLabel()) {
                $element->setLabel($this->getDI()->get('i18n')->_($element->getLabel()));
            }
            $form->add($element);
        }
        return $form;
    }
    
    /**
     * Proxies factory create call to specific responsible trait.
     * @param \Vegas\Forms\InputSettings $settings
     * @return \Phalcon\Forms\ElementInterface form element instance
     * @throws \Vegas\Forms\Builder\Exception\NotFoundException When a specific type is not found
     */
    private function callBuilderMethod(InputSettingsForm $settings)
    {
        $methodName = self::TRAIT_METHOD_PREFIX . ucfirst($settings->getValue(InputSettingsForm::TYPE_PARAM));
        if (!method_exists($this, $methodName)) {
            throw new NotFoundException;
        }
        return $this->$methodName($settings);
    }
    
}