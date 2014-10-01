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
use Vegas\Forms\Element\Birthdaypicker;
use Vegas\Tests\Stub\Models\FakeModel;
use Vegas\Tests\Stub\Models\FakeVegasForm;

class BirthdaypickerTest extends \PHPUnit_Framework_TestCase
{
    protected $di;
    protected $form;
    protected $model;

    protected function setUp()
    {
        $this->di = DI::getDefault();
        $this->model = new FakeModel();
        $this->form = new FakeVegasForm();

        $datepicker = new Birthdaypicker('date');
        $this->form->add($datepicker);
    }

    public function testInput()
    {
        $dateTime = new \DateTime('2014-03-13');
        $dateArray = explode('-', $dateTime->format('Y-m-d'));

        $this->form->bind(array('date' => $dateTime->format('Y-m-d')), $this->model);
        $this->assertEquals($dateArray[0], $this->model->date['year']);
        $this->assertEquals($dateArray[1], $this->model->date['month']);
        $this->assertEquals($dateArray[2], $this->model->date['day']);

        $this->assertEquals($this->form->get('date')->getValue(), $dateTime->format('Y-m-d'));

        // create new form for filled model
        $this->form = new FakeVegasForm($this->model);
        $datepicker = new Birthdaypicker('date');
        $this->form->add($datepicker);
        $this->assertEquals($this->form->get('date')->getValue(), $dateTime->format('Y-m-d'));

        // treat nondate values as normal string
        $testString = 'test string';
        $this->form->bind(array('date' => $testString), $this->model);
        $this->assertEquals($testString, $this->model->date);
    }
}
