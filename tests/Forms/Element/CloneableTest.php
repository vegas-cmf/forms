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

    private function prepareValidCloneableField()
    {
        $cloneable = new Cloneable('cloneable_field');
        $cloneable->setAssetsManager($this->di->get('assets'));

        $cloneable->setBaseElements(array(new \Phalcon\Forms\Element\Text('test1')));
        $cloneable->addBaseElement(new \Phalcon\Forms\Element\Text('test2'));

        return $cloneable;
    }
}
