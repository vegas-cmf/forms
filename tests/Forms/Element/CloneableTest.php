<?php
/**
 * This file is part of Vegas package
 *
 * @author Arkadiusz Ostrycharz <arkadiusz.ostrycharz@gmail.com>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage https://bitbucket.org/amsdard/vegas-phalcon
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Vegas\Tests\Forms\Element;

use Phalcon\DI;
use Phalcon\Validation\Validator\PresenceOf;
use Vegas\Forms\Element\Cloneable;
use Vegas\Tests\Stub\Models\FakeVegasForm;
use Vegas\Tests\Stub\Models\FakeModel;

class CloneableTest extends \PHPUnit_Framework_TestCase
{
    protected $di;
    protected $form;
    
    protected function setUp()
    {
        $this->di = DI::getDefault();
        $this->form = new FakeVegasForm();
    }

    public function testInvalidSetup()
    {
        $cloneable = new Cloneable('cloneable_field');
        
        try {
            $cloneable->render();
        } catch (\Exception $ex) {
            $this->assertInstanceOf('\Vegas\Forms\Element\Exception\InvalidAssetsManagerException', $ex);
        }
        
        $cloneable->setAssetsManager($this->di->get('assets'));
        
        try {
            $cloneable->render();
        } catch (\Exception $ex) {
            $this->assertInstanceOf('\Vegas\Forms\Element\Cloneable\Exception\BaseElementNotSetException', $ex);
        }
        
        $cloneable->setBaseElements(array());
            
        try {
            $cloneable->render();
        } catch (\Exception $ex) {
            $this->assertInstanceOf('\Vegas\Forms\Element\Cloneable\Exception\BaseElementNotSetException', $ex);
        }
        
        $cloneable->setBaseElements(array(new Cloneable('another_cloneable')));
        
        try {
            $cloneable->render();
        } catch (\Exception $ex) {
            $this->assertInstanceOf('\Vegas\Forms\Element\Cloneable\Exception\CantInheritCloneableException', $ex);
        }
    }
    
    public function testCorrectSetup()
    {
        $cloneable = $this->prepareValidCloneableField();
        $this->form->add($cloneable);

        $this->assertEquals(
            '<div vegas-cloneable="1"><fieldset><input type="text" name="cloneable_field[0][test1]" /><input type="text" name="cloneable_field[0][test2]" /></fieldset><fieldset><input type="text" name="cloneable_field[0][test1]" /><input type="text" name="cloneable_field[0][test2]" /></fieldset></div>',
            $this->form->get('cloneable_field')->render()
        );

        $this->assertEquals(
            '<div vegas-cloneable="1"><fieldset attribute="test"><input type="text" name="cloneable_field[0][test1]" /><input type="text" name="cloneable_field[0][test2]" /></fieldset><fieldset attribute="test"><input type="text" name="cloneable_field[0][test1]" /><input type="text" name="cloneable_field[0][test2]" /></fieldset></div>',
            $this->form->get('cloneable_field')->render(array('attribute' => 'test'))
        );

        $this->assertNull($this->form->get('cloneable_field')->getBaseElement('test3'));
        $this->assertInstanceOf('\Phalcon\Forms\ElementInterface', $this->form->get('cloneable_field')->getBaseElement('test2'));
    }

    public function testBinding()
    {
        $model = new FakeModel();

        $cloneable = $this->prepareValidCloneableField();
        $this->form->add($cloneable);

        $this->form->bind(array(
            'cloneable_field' => array(
                array('test1' => 'foo', 'test2' => 'bar'),
                array('test1' => 'baz', 'test2' => 'xyz'),
            )
        ), $model);

        $bindedValues = $this->form->get('cloneable_field')->getValue();

        $this->assertEquals($bindedValues[0]['test1'], 'foo');
        $this->assertEquals($bindedValues[1]['test2'], 'xyz');

        $this->assertEquals($model->cloneable_field[0]['test1'], 'foo');
        $this->assertEquals($model->cloneable_field[1]['test2'], 'xyz');

        $this->assertEquals(
            '<div vegas-cloneable="1"><fieldset><input type="text" name="cloneable_field[0][test1]" /><input type="text" name="cloneable_field[0][test2]" /></fieldset><fieldset><input type="text" name="cloneable_field[0][test1]" value="foo" /><input type="text" name="cloneable_field[0][test2]" value="bar" /></fieldset><fieldset><input type="text" name="cloneable_field[1][test1]" value="baz" /><input type="text" name="cloneable_field[1][test2]" value="xyz" /></fieldset></div>',
            $this->form->get('cloneable_field')->render()
        );
    }

    public function testValidationExtender()
    {
        $cloneable = $this->prepareValidCloneableField();
        $cloneable->getBaseElement('test1')->addValidator(new PresenceOf());

        $this->form->add($cloneable);

        $this->assertFalse($this->form->isValid(array(
            'cloneable_field' => array(
                array('test1' => ''),
                array('test1' => '')
            ),
            'fake_field' => 'foo'
        )));

        $this->assertFalse($this->form->isValid(array(
            'cloneable_field' => array(
                array('test1' => 'bar'),
                array('test1' => '')
            ),
            'fake_field' => 'foo'
        )));

        $this->assertTrue($this->form->isValid(array(
            'cloneable_field' => array(
                array('test1' => 'bar'),
                array('test1' => 'baz')
            ),
            'fake_field' => 'foo'
        )));
    }

    public function testValidationExtenderForCloneableOnly()
    {
        $this->form->get('fake_field')->addValidator(new Cloneable\Validation\Extender());

        $this->assertFalse($this->form->isValid(array(
            'fake_field' => 'foo'
        )));
    }
	
	public function testAddAssetsAddsSortable()
	{
		$cloneable = $this->prepareValidCloneableField();
		$cloneable->setUserOption('sortable', true);
		
		$this->form->add($cloneable);
		$this->form->get('cloneable_field')->getRows();
			
		$sortableAssetPath = 'assets/vendor/html5sortable/jquery.sortable.js';
		$assets = $this->di->get('assets');
		$result = false;
		
		foreach ($assets->getJs()->getResources() as $resource) {
			if ($resource->getPath() === $sortableAssetPath) {
				$result = true;
				break;
			}
		}
        
		$this->assertTrue($result);
	}
	
	public function testRowGet()
	{
		$cloneable = $this->prepareValidCloneableField();
		
		$this->form->add($cloneable);
		$cloneableObj = $this->form->get('cloneable_field');
		
		$rows = $cloneableObj->getRows();
		$test1 = $rows[0];
		$test2 = $test1->get('test2');
		
		$this->assertInstanceOf('\Phalcon\Forms\Element\Text', $test2);
	}
	
	public function testGetSingleFieldNameReturnsOneElementName()
	{
		$cloneableName = 'foo_cloneable';
        $cloneable = new Cloneable($cloneableName);
        $cloneable->setAssetsManager($this->di->get('assets'));
		
		$filedName = 'no_filter';
		$element = new \Phalcon\Forms\Element\Text($filedName);
        $cloneable->addBaseElement($element);
		
		$this->form->add($cloneable);
		$cloneableObj = $this->form->get($cloneableName);
		$rows = $cloneableObj->getRows();
		
		$test1 = $rows[0];
		$expectedValue = 'whatever..';
		$test1->setValues($expectedValue);
		
		$elements = $test1->getElements();
		$this->assertCount(1, $elements);
		$this->assertTrue(isset($elements[$filedName]));
		$this->assertSame($expectedValue, $elements[$filedName]->getValue());
	}

    private function prepareValidCloneableField()
    {
        $cloneable = new Cloneable('cloneable_field');
		
        $assets = $this->di->get('assets');
        $cloneable->setAssetsManager($assets);
        $this->assertSame($assets, $cloneable->getAssetsManager());

        $cloneable->setBaseElements(array(new \Phalcon\Forms\Element\Text('test1')));
        $cloneable->addBaseElement(new \Phalcon\Forms\Element\Text('test2'));
        return $cloneable;
    }
}
