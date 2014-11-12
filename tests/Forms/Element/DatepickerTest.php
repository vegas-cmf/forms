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
use Vegas\Forms\Element\Datepicker;
use Vegas\Tests\Stub\Models\FakeModel;
use Vegas\Tests\Stub\Models\FakeVegasForm;

class DatepickerTest extends \PHPUnit_Framework_TestCase
{
    protected $di;
    protected $form;
    protected $model;

    protected function setUp()
    {
        $this->di = DI::getDefault();
        $this->model = new FakeModel();
        $this->form = new FakeVegasForm();

        $datepicker = new Datepicker('date');
        $this->form->add($datepicker);
    }

    public function testInput()
    {
        $dateTime = new \DateTime('2014-03-13');

        $this->form->bind(array('date' => $dateTime->format('Y-m-d')), $this->model);
        $this->assertEquals($dateTime->getTimestamp(), $this->model->date);
        $this->assertEquals($this->form->get('date')->getValue(), $dateTime->format('Y-m-d'));

        // create new form for filled model
        $this->form = new FakeVegasForm($this->model);
        $datepicker = new Datepicker('date');
        $this->form->add($datepicker);
        $this->assertEquals($this->form->get('date')->getValue(), $dateTime->format('Y-m-d'));

        // treat nondate values as normal string
        $testString = 'test string';
        $this->form->bind(array('date' => $testString), $this->model);
        $this->assertEquals($testString, $this->model->date);
    }

    public function testRender()
    {
        $this->assertNull($this->form->get('date')->getAssetsManager());

        try {
            $this->form->get('date')->render();
            throw new \Exception('Not this exception.');
        } catch (\Exception $ex) {
            $this->assertInstanceOf('\Vegas\Forms\Element\Exception\InvalidAssetsManagerException', $ex);
        }

        $this->form->get('date')->setAssetsManager($this->di->get('assets'));

        $this->assertInstanceOf('\Phalcon\Assets\Manager', $this->form->get('date')->getAssetsManager());
        $this->assertEquals('<input type="text" id="date" name="date" vegas-datepicker="1" />', $this->form->get('date')->render());
    }
}
