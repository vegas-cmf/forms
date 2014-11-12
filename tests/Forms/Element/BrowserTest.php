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
use Vegas\Tests\Stub\Models\FakeModel;
use Vegas\Tests\Stub\Models\FakeVegasForm;

class BrowserTest extends \PHPUnit_Framework_TestCase
{
    protected $di;
    protected $form;
    protected $model;

    protected function setUp()
    {
        $this->di = DI::getDefault();
        $this->model = new FakeModel();
        $this->form = new FakeVegasForm();

        $browser = new Browser('browser');
        $this->form->add($browser);
    }

    public function testInput()
    {
        $this->form->bind(array('browser' => '/some/url.jpg'), $this->model);
        $this->assertEquals($this->form->get('browser')->getValue(), $this->model->browser);
    }

    public function testRender()
    {
        $html = <<<RENDER
<div class="input-group browser-wrapper">
                    <input type="text" id="browser" name="browser" vegas-browser="1" />
                    <div class="input-group-btn">
                        <a class="btn btn-primary btn-browse">Browse</a>
                    </div>
                </div>
RENDER;

        $this->assertEquals($html, $this->form->get('browser')->render());
    }
}
