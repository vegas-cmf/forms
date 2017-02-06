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
use Vegas\Forms\Element\Browser;
use Vegas\Forms\Element\Check;
use Vegas\Forms\Element\Radio;
use Vegas\Forms\Element\RadioGroup;
use Vegas\Forms\Element\Text;
use Vegas\Tests\Stub\Models\FakeModel;
use Vegas\Tests\Stub\Models\FakeVegasForm;

class RadioGroupTest extends \PHPUnit_Framework_TestCase
{
    protected $di;
    protected $form;
    protected $model;

    protected function setUp()
    {
        $this->di = DI::getDefault();
        $this->model = new FakeModel();
        $this->form = new FakeVegasForm();

        $left = new Radio('left');
        $left->setAttribute('name', 'type');
        $left->setAttribute('value', 'left');

        $right = new Radio('right');
        $right->setAttribute('name', 'type');
        $right->setAttribute('value', 'right');

        $field = new RadioGroup('type');
        $field->setLabel('Type');
        $field->setElements([$left, $right]);
        $this->form->add($field);
    }

    public function testInput()
    {
        $this->form->bind(array('type' => 'left'), $this->model);
        $value = $this->form->get('type')->getValue();
        $this->assertEquals($value, $this->model->type);
        $elements = $this->form->get('type')->getElements();
        foreach ($elements as $element) {
            if ($element->getName() == $value) {
                $this->assertSame($value, $element->getValue());
            }
        }
    }

    public function testRender()
    {
        $html = <<<RENDER
<div class="clearfix form-group">
        <span style="margin-right: 30px;">
        <input type="radio" id="left" name="type" value="left" />        <label for="left">left</label>
    </span>
        <span style="margin-right: 30px;">
        <input type="radio" id="right" name="type" value="right" />        <label for="right">right</label>
    </span>
    </div>
RENDER;

        $this->assertEquals($html, $this->form->get('type')->render());
    }
}
