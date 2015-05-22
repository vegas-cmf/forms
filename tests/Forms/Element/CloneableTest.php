<?php
/**
 * This file is part of Vegas package
 *
 * @author Arkadiusz Ostrycharz <aostrycharz@amsterdam-standard.pl>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage http://vegas-cmf.github.io/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Vegas\Tests\Forms\Element;

use Phalcon\DI;
use Phalcon\Validation\Validator\PresenceOf;
use Vegas\Forms\Element\Cloneable;
use Vegas\Forms\Element\Datepicker;
use Vegas\Forms\Element\Text;
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

    /**
     * @expectedException \Vegas\Forms\Element\Cloneable\Exception\BaseElementNotSetException
     */
    public function testBaseElementNotSet()
    {
        $cloneable = new Cloneable('cloneable_field');

        $cloneable->render();
    }

    /**
     * @expectedException \Vegas\Forms\Element\Cloneable\Exception\BaseElementNotSetException
     */
    public function testBaseElementSetEmptyArray()
    {
        $cloneable = new Cloneable('cloneable_field');
        $cloneable->setBaseElements([]);

        $cloneable->render();
    }

    /**
     * @expectedException \Vegas\Forms\Element\Cloneable\Exception\CantInheritCloneableException
     */
    public function testOtherCloneableInheritance()
    {
        $cloneable = new Cloneable('cloneable_field');
        $cloneable->setBaseElements([new Cloneable('another_cloneable')]);

        $cloneable->render();
    }

    /**
     * @expectedException \Vegas\Forms\Decorator\Exception\DiNotSetException
     */
    public function testDiNotSetSetup()
    {
        $cloneable = new Cloneable('cloneable_field');
        $cloneable->setBaseElements([new Text('another_cloneable')]);

        $this->assertNull($cloneable->getDecorator()->getDI());

        $cloneable->render();
    }


    public function testCorrectSetup()
    {
        $cloneable = $this->prepareValidCloneableField();
        $this->form->add($cloneable);

        $this->assertInstanceOf('\Phalcon\DI', $this->form->get('cloneable_field')->getDecorator()->getDI());

        $this->form->get('cloneable_field')->getDecorator()->setTemplateName('jquery');

        $domDoc = new \DOMDocument('1.0');
        $domDoc->loadHTML($this->form->get('cloneable_field')->render());

        $this->assertEquals(2, $domDoc->getElementById('cloneable_field')->getElementsByTagName('fieldset')->length);
        $this->assertEquals(4, $domDoc->getElementById('cloneable_field')->getElementsByTagName('input')->length);

        $domDoc->loadHTML($this->form->get('cloneable_field')->render(['attribute' => 'test']));

        $this->assertEquals('test', $domDoc->getElementById('cloneable_field')->attributes->getNamedItem('attribute')->value);
        $this->assertNull($this->form->get('cloneable_field')->getBaseElement('test3'));
        $this->assertInstanceOf('\Phalcon\Forms\ElementInterface', $this->form->get('cloneable_field')->getBaseElement('test2'));
    }

    public function testBinding()
    {
        $model = new FakeModel();

        $cloneable = $this->prepareValidCloneableField();

        $datepicker = new Datepicker('date');

        $cloneable->addBaseElement($datepicker);

        $this->form->add($datepicker);
        $this->form->add($cloneable);

        $this->form->bind([
            'date' => '2014-03-01',
            'cloneable_field' => [
                ['test1' => 'foo', 'test2' => 'bar'],
                ['test1' => 'baz', 'test2' => 'xyz', 'date' => '2014-03-01'],
            ]
        ], $model);

        $bindedValues = $this->form->get('cloneable_field')->getValue();

        $this->assertEquals($bindedValues[0]['test1'], 'foo');
        $this->assertEquals($bindedValues[1]['test2'], 'xyz');
        $this->assertEquals($bindedValues[1]['date'], $this->form->get('date')->getValue());

        $this->assertEquals('foo', $model->cloneable_field[0]['test1']);
        $this->assertEquals('xyz', $model->cloneable_field[1]['test2']);
        $this->assertEquals($model->date, $model->cloneable_field[1]['date']); // int 1393628400

        $this->form->get('cloneable_field')->getDecorator()->setTemplateName('jquery');
    }

    public function testValidationExtender()
    {
        $cloneable = $this->prepareValidCloneableField();
        $cloneable->getBaseElement('test1')->addValidator(new PresenceOf());
        $this->form->add($cloneable);

        $this->assertFalse($this->form->isValid([
            'cloneable_field' => [
                ['test1' => ''],
                ['test1' => '']
            ],
            'fake_field' => 'foo'
        ]));

        $this->assertFalse($this->form->isValid([
            'cloneable_field' => [
                ['test1' => 'bar'],
                ['test1' => '']
            ],
            'fake_field' => 'foo'
        ]));

        $this->assertTrue($this->form->isValid([
            'cloneable_field' => [
                ['test1' => 'bar'],
                ['test1' => 'baz']
            ],
            'fake_field' => 'foo'
        ]));
    }

    public function testValidationExtenderForCloneableOnly()
    {
        $this->form->get('fake_field')->addValidator(new Cloneable\Validation\Extender());

        $this->assertFalse($this->form->isValid([
            'fake_field' => 'foo'
        ]));
    }

	public function testRowGet()
	{
		$cloneable = $this->prepareValidCloneableField();
		$this->form->add($cloneable);

		$cloneableObj = $this->form->get('cloneable_field');

		$rows = $cloneableObj->getRows();
		$test1 = $rows[0];
		$test2 = $test1->get('test2');

		$this->assertInstanceOf('\Vegas\Forms\Element\Text', $test2);
	}

	public function testGetSingleFieldNameReturnsOneElementName()
	{
		$cloneableName = 'foo_cloneable';
        $cloneable = new Cloneable($cloneableName);

		$filedName = 'no_filter';
		$element = new Text($filedName);
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
        $cloneable->setBaseElements([new Text('test1')]);
        $cloneable->addBaseElement(new Text('test2'));
        $cloneable->getDecorator()->setDI($this->di);
        return $cloneable;
    }
}
