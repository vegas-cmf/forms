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
use Vegas\Forms\Element\Colorpicker;
use Vegas\Tests\Stub\Models\FakeModel;
use Vegas\Tests\Stub\Models\FakeVegasForm;

class ColorpickerTest extends \PHPUnit_Framework_TestCase
{
    protected $di;
    protected $form;
    protected $model;

    protected function setUp()
    {
        $this->di = DI::getDefault();

        $this->model = new FakeModel();
        $this->form = new FakeVegasForm();

        $content = new Colorpicker('content');
        $content->setAttribute('class', 'test1');
        $this->form->add($content);
    }

    public function testRender()
    {
        $testElement = (new Text('content'))
            ->setAttribute('class', 'test1');

        $this->assertEquals(
            $testElement->render(),
            $this->form->get('content')->render()
        );

        $attributes = ['name' => 'foobaz'];

        $this->assertEquals($testElement->render($attributes), $this->form->get('content')->render($attributes));

        $this->regenerateForm();

        $this->form->get('content')->getDecorator()->setTemplateName('jquery');

        $this->assertEquals('<input type="text" id="content" name="content" class="test1" value="#f0f0f0" vegas-colorpicker />', $this->form->get('content')->renderDecorated());
        $this->assertEquals('<input type="text" id="content" name="content" class="test1" value="#f0f0f0" vegas-colorpicker />', $this->form->get('content')->renderDecorated(['value' => '#f0f0f0']));
    }

    private function regenerateForm()
    {
        $this->model->content = '#abcdef';
        $this->form = new FakeVegasForm($this->model);

        $content = new Colorpicker('content');
        $content->setAttribute('class', 'test1');
        $content->getDecorator()->setDI($this->di);

        $this->form->add($content);
        $this->form->bind(['content' => '#f0f0f0'], $this->model);
    }
}
