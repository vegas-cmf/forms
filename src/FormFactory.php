<?php
/**
 * This file is part of Vegas package
 *
 * @author Radosław Fąfara <radek@archdevil.pl>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage http://vegas-cmf.github.io/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Vegas\Forms;

use Phalcon\DI\InjectionAwareInterface;
use Vegas\Forms\Form as GenericForm;
use Vegas\Forms\InputSettings as InputSettingsForm;
use Vegas\Forms\Builder\Exception\InvalidInputSettingsException;
use Vegas\Forms\Builder\Exception\NotDefinedException;
use Vegas\Forms\Builder\Exception\NotFoundException;

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
     * Common prefix for all builder methods used by this factory
     */
    const BUILDER_METHOD = 'build';

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
    public function setDI(\Phalcon\DiInterface $dependencyInjector)
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

    /**
     * @param $builderClass
     * @throws NotFoundException
     */
    public function addBuilder($builderClass)
    {
        if(!class_exists($builderClass)) {
            throw new NotFoundException();
        }

        if(!$this->checkIfBuilderAlreadyExists($builderClass)) {
            $this->builders[] = $builderClass;
        }
    }

    /**
     * @param $builderClass
     * @return bool
     */
    public function checkIfBuilderAlreadyExists($builderClass)
    {
        return in_array($builderClass, $this->builders);
    }


    /**
     * Acts as factory pattern: generates form object with all dependent elements.
     * Each element should be represented by a specific trait.
     * Uses i18n for provided labels.
     *
     * @param array $data each form element data
     * @return \Vegas\Forms\Form full instance of form
     * @throws \Vegas\Forms\Builder\Exception\InvalidInputSettingsException When provided invalid (manipulated) input
     */
    public function createForm(array $data)
    {
        $form = new GenericForm;
        foreach ($data as $item) {

            $element = $this->callBuilderMethod($item);
            if ($element->getLabel()) {
                $element->setLabel($this->getDI()->get('i18n')->_($element->getLabel()));
            }
            $additionalOptions = $this->callAdditionalOptionsMethod($item);

            if($additionalOptions !== null) {
                foreach($additionalOptions as $option) {
                    if(isset($item[$option->getName()])) {
                        $element->setAttribute($option->getName(), $item[$option->getName()]);
                    }
                }
            }
            $form->add($element);
        }
        return $form;
    }

    /**
     * Proxies factory create call to specific responsible builder.
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

        $method = new \ReflectionMethod($className, self::BUILDER_METHOD);
        return $method->invokeArgs(new $className, array($settings));
    }

    /**
     * Proxies factory create call for additional fields to specific responsible builder.
     * @param array $settings
     * @return \Phalcon\Forms\ElementInterface form element instance
     * @throws \Vegas\Forms\Builder\Exception\NotFoundException When a specific type is not found
     */
    private function callAdditionalOptionsMethod($item)
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

        $builderObject = new $className;
        $builderObject->setAdditionalOptions();
        return $builderObject->getAdditionalOptions();
    }

    /**
     * Method create object for render each element. Execute initElement method
     * @return array
     */
    public function render()
    {
        $elements = [];

        foreach($this->builders as $builder) {
            $object = new $builder;
            $elements[] = [
                'element' => $object->initElement(),
                'options' => $object->getAdditionalOptions()
                ];
        }
        return $elements;
    }

}
