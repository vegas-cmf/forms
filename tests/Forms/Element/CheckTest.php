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
use Vegas\Forms\Element\Text;
use Vegas\Tests\Stub\Models\FakeModel;
use Vegas\Tests\Stub\Models\FakeVegasForm;

class CheckTest extends \PHPUnit_Framework_TestCase
{
    protected $di;
    protected $form;
    protected $model;

    protected function setUp()
    {
        $this->di = DI::getDefault();
        $this->model = new FakeModel();
        $this->form = new FakeVegasForm();

        $check = new Check('check');
        $check->setAttribute('value', '1');
        $this->form->add($check);
    }

    public function testInput()
    {
        $this->form->bind(array('check' => '1'), $this->model);
        $this->assertEquals($this->form->get('check')->getValue(), $this->model->check);
    }

    public function testRender()
    {
        $html = <<<RENDER
<input type="hidden" name="check" value="0" />
<input type="checkbox" id="check" name="check" value="1" />
RENDER;

        $this->assertEquals($html, $this->form->get('check')->renderDecorated());
    }
}
