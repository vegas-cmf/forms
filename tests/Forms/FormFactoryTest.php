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
namespace Vegas\Tests\Forms;

use Phalcon\DI,
    Vegas\Forms\FormFactory,
    Vegas\Forms\DataProvider\DataProviderInterface;

/**
 * Used to mock translations using DI.
 * @package VegasTest\Forms
 */
class FakeTranslator
{
    public function _($message)
    {
        return $message;
    }
}

/**
 * Used as an example of dropdown list data providers (see setUp() below).
 * Class FakeDataProvider
 * @package VegasTest\Forms
 */
class FakeDataProvider implements DataProviderInterface
{
    public function getData()
    {
        return [
            'bonita'            => 'Bonita (the Noboa Company)',
            'chiquita'          => 'Chiquita',
            'del_monte'         => 'Del Monte',
            'dole_food_company' => 'Dole Food Company'
        ];
    }

    public function getName()
    {
        return 'Banana companies';
    }

    public function setOptions(array $options)
    {
        // Not needed actually in this example
    }
}

/**
 * Main test case.
 */
class FormFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $formFactory;
    
    protected function setUp()
    {
        $this->formFactory = new FormFactory;
        $di = DI::getDefault();
        $di->set('i18n', new FakeTranslator);
        $di->set('formFactory', $this->formFactory);    // The factory is used as a shared service.
        $di->set('config', new \Phalcon\Config([
            'formFactory'   => [
                'dataProviders' => [
                    // Keeps all classes used to provide data for multiple data input types.
                    // Use fully qualified class names implementing DataProviderInterface as values.
                    // The order here is how these options will be listed in the form when selecting one.
                    '\Vegas\Tests\Forms\FakeDataProvider'
                ]
            ]
        ]));
        parent::setUp();
    }
    
    /**
     * Each of builders is "type" param for created inputs.
     */
    public function testFormFactoryHasAvailableInputBuilders()
    {
        $this->assertTrue(method_exists($this->formFactory, 'buildEmail'));
        $this->assertTrue(method_exists($this->formFactory, 'buildDatepicker'));
        $this->assertTrue(method_exists($this->formFactory, 'buildSelect'));
    }
    
    public function testCreateEmptyDynamicForm()
    {
        $data = [];        
        $form = $this->formFactory->createForm($data);
        $this->assertInstanceOf('\Vegas\Forms\Form', $form);
        $this->assertEmpty($form->getElements());
    }
    
    public function testCreateDynamicFormWithStaticElements()
    {
        $data = [
            [   // Each array is an input element to be added.
                // Keys used are specified in \Vegas\Forms\InputSettings as constants.
                'name'      => 'userEmail',
                'type'      => 'Email',
                'required'  => true,
                'label'     => 'Fill e-mail address'
            ],
            [
                'name'      => 'userBirthdate',
                'type'      => 'Datepicker',
                'required'  => true,
                'label'     => 'Fill birthdate'
            ],
        ];
        
        $form = $this->formFactory->createForm($data);
        $this->assertEquals(2, count($form->getElements()));
        
        $validData = [
            'userEmail'     => 'test@example.com',
            'userBirthdate' => '2014-01-01'
        ];
        $invalidData = [
            'userEmail'     => 'whatever',
            'userBirthdate' => '2014-01-01'
        ];
        
        $this->assertTrue($form->isValid($validData));
        $this->assertFalse($form->isValid($invalidData));
    }
    
    public function testCreateDynamicFormWithDynamicData()
    {
        $data = [
            [
                'name'      => 'bananaCompanies',
                'type'      => 'Select',
                'required'  => false,
                'label'     => 'Select company',
                'data'      => '\Vegas\Tests\Forms\FakeDataProvider'
            ]
        ];
        
        $form = $this->formFactory->createForm($data);
        $data[0]['required'] = true;
        $requiredForm = $this->formFactory->createForm($data);
        
        $this->assertEquals(1, count($form->getElements()));
        $this->assertEquals(1, count($requiredForm->getElements()));
        
        $validData = ['bananaCompanies' => 'chiquita'];
        $invalidData = ['bananaCompanies' => 'whatever'];
        $emptyData = [];
        
        $this->assertTrue($form->isValid($validData));
        $this->assertFalse($form->isValid($invalidData));
        $this->assertTrue($form->isValid($emptyData));
        
        $this->assertTrue($requiredForm->isValid($validData));
        $this->assertFalse($requiredForm->isValid($invalidData));
        $this->assertFalse($requiredForm->isValid($emptyData));
    }
    
    public function testCreateDynamicFormTextData()
    {
        $data = [
            [
                'name'      => 'firstname',
                'type'      => 'Text',
                'required'  => true,
                'label'     => 'Your name'
            ],
            [
                'name'      => 'contentField',
                'type'      => 'RichTextArea',
                'required'  => true,
                'label'     => 'Your comments'
            ]
        ];
        
        $form = $this->formFactory->createForm($data);
        $this->assertEquals(2, count($form->getElements()));
        
        $validData = ['firstname' => 'John Doe', 'contentField' => 'Foo is really bar.'];
        $invalidData = ['firstname' => 'John Doe', 'contentField' => ''];
        
        $this->assertTrue($form->isValid($validData));
        $this->assertFalse($form->isValid($invalidData));
    }
    
    public function testManipulatedInputSettings()
    {
        $data = [['type' => 'Text']];
        try {
            $this->formFactory->createForm($data);
        } catch (\Exception $e) {
            $this->assertInstanceOf('\Vegas\Forms\Exception\InvalidInputSettingsException', $e);
        }
    }
    
    public function testNonexistentBuilderType()
    {
        $data = [
            [
                'name'      => uniqid(),
                'type'      => 'NonExistingClass'
            ]
        ];
        try {
            $this->formFactory->createForm($data);
        } catch (\Exception $e) {
            $this->assertInstanceOf('\Vegas\Forms\Builder\Exception\NotFoundException', $e);
        }
    }
    
    public function testNonexistentDataProvider()
    {
        $data = [
            [
                'name'      => 'genders',
                'type'      => 'Select',
                'required'  => true,
                'data'      => 'NonExistingDataProvider'
            ]
        ];
        try {
            $this->formFactory->createForm($data);
        } catch (\Exception $e) {
            $this->assertInstanceOf('\Vegas\Forms\DataProvider\Exception\NotFoundException', $e);
        }
    }
}
