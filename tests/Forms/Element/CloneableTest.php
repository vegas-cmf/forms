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
use Vegas\Tests\Stub\Models\FakeForm;
use Vegas\Tests\Stub\Models\FakeModel;

class CloneableTest extends \PHPUnit_Framework_TestCase
{
    protected $di;
    protected $form;
    
    protected function setUp()
    {
        $this->di = DI::getDefault();
        $this->form = new FakeForm();
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
        
        $this->assertTrue(is_string($this->form->render('cloneable_field')));
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

        $values = $this->form->getValue('cloneable_field');

        $this->assertEquals($values[0]['test1'], 'foo');
        $this->assertEquals($values[1]['test2'], 'xyz');
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
