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
        try {
            $this->form->get('content')->render();
            throw new \Exception('Not this exception.');
        } catch (\Exception $ex) {
            $this->assertInstanceOf('\Vegas\Forms\Decorator\Exception\DiNotSetException', $ex);
        }

        $this->form->get('content')->setDecoratorDi($this->di);

        $this->assertEquals('<input type="text" class="test1" name="content" value=""/>', $this->form->get('content')->render());

        $this->form->get('content')->getDecorator()->setTemplateName('bootstrap');
        $this->assertEquals('<input type="text" class="test1" name="content" value="" vegas-colorpicker />', $this->form->get('content')->render());
    }
}
