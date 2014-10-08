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

        $this->assertNull($cloneable->getDecorator()->getDI());

        try {
            $cloneable->renderDecorated();
            throw new \Exception('Not this exception.');
        } catch (\Exception $ex) {
            $this->assertInstanceOf('\Vegas\Forms\Decorator\Exception\DiNotSetException', $ex);
        }

        $cloneable->getDecorator()->setDI($this->di);

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

        $this->assertInstanceOf('\Phalcon\DI', $this->form->get('cloneable_field')->getDecorator()->getDI());

        $html = <<<RENDERED
<div id="cloneable_field" vegas-cloneable>
    <fieldset>
        <input type="text" name="cloneable_field[0][test1]" />
        <input type="text" name="cloneable_field[0][test2]" />
    </fieldset>
    <fieldset>
        <input type="text" name="cloneable_field[0][test1]" />
        <input type="text" name="cloneable_field[0][test2]" />
    </fieldset>
</div>
RENDERED;

        $this->form->get('cloneable_field')->getDecorator()->setTemplateName('jquery');

        $this->assertEquals(
            $html,
            $this->form->get('cloneable_field')->render()
        );

        $this->assertEquals(
            $html,
            $this->form->get('cloneable_field')->renderDecorated()
        );

        $htmlWithAttr = <<<RENDERED
<div id="cloneable_field" attribute="test" vegas-cloneable>
    <fieldset>
        <input type="text" name="cloneable_field[0][test1]" />
        <input type="text" name="cloneable_field[0][test2]" />
    </fieldset>
    <fieldset>
        <input type="text" name="cloneable_field[0][test1]" />
        <input type="text" name="cloneable_field[0][test2]" />
    </fieldset>
</div>
RENDERED;

        $this->assertEquals(
            $htmlWithAttr,
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

        $html = <<<RENDERED
<div id="cloneable_field" vegas-cloneable>
    <fieldset>
        <input type="text" name="cloneable_field[0][test1]" />
        <input type="text" name="cloneable_field[0][test2]" />
    </fieldset>
    <fieldset>
        <input type="text" name="cloneable_field[0][test1]" value="foo" />
        <input type="text" name="cloneable_field[0][test2]" value="bar" />
    </fieldset>
    <fieldset>
        <input type="text" name="cloneable_field[1][test1]" value="baz" />
        <input type="text" name="cloneable_field[1][test2]" value="xyz" />
    </fieldset>
</div>
RENDERED;

        $this->form->get('cloneable_field')->getDecorator()->setTemplateName('jquery');

        $this->assertEquals(
            $html,
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
        $cloneable->setBaseElements(array(new \Phalcon\Forms\Element\Text('test1')));
        $cloneable->addBaseElement(new \Phalcon\Forms\Element\Text('test2'));
        $cloneable->getDecorator()->setDI($this->di);
        return $cloneable;
    }
}
