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
use Vegas\Forms\Builder\Exception\NotFoundException;
use Vegas\Forms\BuilderAbstract;
use Vegas\Forms\Form;

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
        $di = DI::getDefault();
        $this->formFactory = new FormFactory;
        $this->formFactory->setDI($di);
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
     * @expectedException \Vegas\Forms\Builder\Exception\NotFoundException
     */
    public function testExceptionWhenClassNotExists()
    {
        $this->formFactory->addBuilder('\Invalid\Class\Not\Existed');

    }

    /**
     * @expectedException \Vegas\Forms\Builder\Exception\NotDefinedException
     */
    public function testExceptionWhenNotDefinedCallingBuilder()
    {
        $data = [
            [
                'name'      => 'fakeUser',
                'type'      => '\Vegas\Tests\Stub\Models\FakeBuilder',
                'required'  => true,
                'label'     => 'Fill fake user'
            ]
        ];
        $this->formFactory->createForm($data);
    }

    /**
     * Each of builders is "type" param for created inputs.
     */
    public function testFormFactoryIsAbleToAddBuilder()
    {
        $this->formFactory->addBuilder('\Vegas\Tests\Stub\Models\FakeBuilder');

        $elements = $this->formFactory->getAvailableInputs();
        $this->assertTrue(in_array('FakeBuilder', $elements));

        $data = [
            [
                'name'      => 'fakeUser',
                'type'      => '\Vegas\Tests\Stub\Models\FakeBuilder',
                'required'  => true,
                'label'     => 'Fill fake user'
            ],
            [
                'name'      => 'fakeUser2',
                'type'      => '\Vegas\Tests\Stub\Models\FakeBuilder',
                'required'  => true,
                'label'     => 'Fill fake user'
            ],
            [
                'name'      => 'fakeUser3',
                'type'      => '\Vegas\Tests\Stub\Models\FakeBuilder',
                'required'  => true,
                'label'     => 'Fill fake user'
            ],
            [
                'name'      => 'fakeUser4',
                'type'      => '\Vegas\Tests\Stub\Models\FakeBuilder',
                'required'  => true,
                'label'     => 'Fill fake user'
            ],
            [
                'name'      => 'fakeUser5',
                'type'      => '\Vegas\Tests\Stub\Models\FakeBuilder',
                'required'  => true,
                'label'     => 'Fill fake user'
            ],
        ];

        $form = $this->formFactory->createForm($data);
        $this->assertEquals(5, count($form->getElements()));

    }

    public function testCreateEmptyDynamicForm()
    {
        $data = [];
        $form = $this->formFactory->createForm($data);
        $this->assertInstanceOf('\Vegas\Forms\Form', $form);
        $this->assertEmpty($form->getElements());
    }

    public function testCreateDynamicForm()
    {
        $data = [
            [
                'name'      => 'userBirthdate',
                'type'      => '\Vegas\Forms\Builder\Datepicker',
                'required'  => true,
                'label'     => 'Fill birthdate',
                'format'    => 'Y-m-d'
            ],
            [
                'name'      => 'userEmail',
                'type'      => '\Vegas\Forms\Builder\Email',
                'required'  => true,
                'label'     => 'Fill e-mail address'
            ],
            [
                'name'      => 'userPassword',
                'type'      => '\Vegas\Forms\Builder\Password',
                'required'  => true,
                'label'     => 'Fill password'
            ],
            [
                'name'      => 'userName',
                'type'      => '\Vegas\Forms\Builder\Text',
                'required'  => true,
                'label'     => 'Fill name'
            ],
        ];

        $form = $this->formFactory->createForm($data);
        $this->assertEquals(4, count($form->getElements()));

        $validData = [
            'userEmail'     => 'test@example.com',
            'userPassword' => 'mySecretP@$sw0rd',
            'userBirthdate' => '2014-01-01',
            'userName' => 'Lester123'
        ];

        $this->assertTrue($form->isValid($validData));

    }
    
    public function testCreateDynamicFormWithStaticElements()
    {
        $data = [
            [   // Each array is an input element to be added.
                // Keys used are specified in \Vegas\Forms\InputSettings as constants.
                'name'      => 'userEmail',
                'type'      => '\Vegas\Forms\Builder\Email',
                'required'  => true,
                'label'     => 'Fill e-mail address'
            ],
            [
                'name'      => 'userBirthdate',
                'type'      => '\Vegas\Forms\Builder\Datepicker',
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
                'type'      => '\Vegas\Forms\Builder\Select',
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
                'type'      => '\Vegas\Forms\Builder\Text',
                'required'  => true,
                'label'     => 'Your name'
            ],
            [
                'name'      => 'contentField',
                'type'      => '\Vegas\Forms\Builder\RichTextArea',
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
                'type'      => '\Vegas\Forms\Builder\NonExistingClass'
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
                'type'      => '\Vegas\Forms\Builder\Select',
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

    public function testRenderElements()
    {
        $elements = $this->formFactory->render();
        $this->assertEquals(6, count($elements));
        foreach($elements as $element){
            $this->assertTrue($element['element'] instanceof \Phalcon\Forms\Element);
        }
    }
}
