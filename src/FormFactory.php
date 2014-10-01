<?php
/**
 * This file is part of Vegas package
 *
 * @author Radosław Fąfara <radek@archdevil.pl>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage https://github.com/vegas-cmf
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
use Vegas\Forms\Builder\Exception\NotDefinedException;

class FormFactory implements InjectionAwareInterface
{
    /**
     * Stores custom builder traits to use
     * @type array
     */
    private $builders = [
        '\Vegas\Forms\Builder\Text',
        '\Vegas\Forms\Builder\Password',
        '\Vegas\Forms\Builder\Select',
        '\Vegas\Forms\Builder\Datepicker',
        '\Vegas\Forms\Builder\Email',
        '\Vegas\Forms\Builder\RichTextArea',
    ];

    /**
     * Common prefix for all trait builder methods used by this factory
     */
    const METHOD_NAME = 'build';

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
        $values = preg_replace('/.*\\\/', '', $this->builders);
        return array_combine($this->builders, $values);
    }

    public function addBuilder($builderClass)
    {
        if(!class_exists($builderClass)) {
            throw new NotFoundException();
        }
        $this->builders[] = $builderClass;
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
            $element = $this->callBuilderMethod($item);
            if ($element->getLabel()) {
                $element->setLabel($this->getDI()->get('i18n')->_($element->getLabel()));
            }
            $form->add($element);
        }
        return $form;
    }

    /**
     * Proxies factory create call to specific responsible trait.
     * @param array $settings
     * @return \Phalcon\Forms\ElementInterface form element instance
     * @throws \Vegas\Forms\Builder\Exception\NotFoundException When a specific type is not found
     */
    private function callBuilderMethod($item)
    {
        $settings = new InputSettingsForm;
        if (!$settings->isValid($item)) {
            throw new InvalidInputSettingsException;
        }
        $settings->bind($item, new \stdClass);

        $className = $item[InputSettingsForm::TYPE_PARAM];

        if(!class_exists($className)) {
            throw new NotFoundException();
        }
        if(!in_array($className, $this->builders)) {
            throw new NotDefinedException();
        }

        $method = new \ReflectionMethod($className, self::METHOD_NAME);
        return $method->invokeArgs(new $className, array($settings));
    }

    /**
     * Method create object for render each element. Execute initElement method
     * @return array
     */
    public function render()
    {
        $elements = [];
        foreach($this->builders as $builder) {
            $elements[] = (new $builder)->initElement();
        }
        return $elements;
    }

}