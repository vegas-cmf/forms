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

        $datepicker = new Timepicker('date');
        $this->form->add($datepicker);
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
        $this->assertEquals('<input type="text" id="date" name="date" vegas-timepicker="1" />', $this->form->get('date')->render());
    }
}
