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
use Phalcon\Forms\Element\Text;
use Vegas\Forms\Element\Timepicker;
use Vegas\Tests\Stub\Models\FakeModel;
use Vegas\Tests\Stub\Models\FakeVegasForm;

class TimepickerTest extends \PHPUnit_Framework_TestCase
{
    protected $di;
    protected $form;
    protected $model;

    protected function setUp()
    {
        $this->di = DI::getDefault();
        $this->model = new FakeModel();
        $this->form = new FakeVegasForm();

        $timepicker = new Timepicker('date');
        $timepicker->setAttribute('class', 'test1');

        $this->form->add($timepicker);
    }

    public function testRender()
    {
        $testElement = (new Text('date'))
            ->setAttribute('class', 'test1');

        $this->assertEquals(
            $testElement->render(),
            $this->form->get('date')
        );

        $this->form->get('date')->getDecorator()->setTemplateName('jquery');

        $attributes = ['name' => 'foobaz'];

        $this->assertEquals($testElement->render($attributes), $this->form->get('date')->render($attributes));

        $this->regenerateForm();
        $this->form->get('date')->getDecorator()->setTemplateName('jquery');

        $this->assertEquals('<input type="text" id="date" name="date" class="test1" value="10:20" vegas-timepicker />', $this->form->get('date')->renderDecorated());
        $this->assertEquals('<input type="text" id="date" name="date" class="test1" value="10:30" vegas-timepicker />', $this->form->get('date')->renderDecorated(['value' => '10:30']));
    }

    private function regenerateForm()
    {
        $this->model->content = '#abcdef';
        $this->form = new FakeVegasForm($this->model);

        $timepicker = new Timepicker('date');
        $timepicker->setAttribute('class', 'test1');
        $timepicker->getDecorator()->setDI($this->di);

        $this->form->add($timepicker);
        $this->form->bind(['date' => '10:20'], $this->model);
    }
}
