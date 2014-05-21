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
        $this->form->add($content);
    }

    public function testInput()
    {
        // without additional validation any string can pass here
        $testString = '#000FFF';

        $this->form->bind(array('content' => $testString), $this->model);
        $this->assertEquals($testString, $this->model->content);
        $this->assertEquals($this->form->get('content')->getValue(), $testString);
    }

    public function testRender()
    {
        $this->assertNull($this->form->get('content')->getAssetsManager());

        try {
            $this->form->get('content')->render();
        } catch (\Exception $ex) {
            $this->assertInstanceOf('\Vegas\Forms\Element\Exception\InvalidAssetsManagerException', $ex);
        }

        $this->form->get('content')->setAssetsManager($this->di->get('assets'));

        $this->assertInstanceOf('\Phalcon\Assets\Manager', $this->form->get('content')->getAssetsManager());
        $this->assertEquals('<input type="text" id="content" name="content" vegas-colorpicker="1" />', $this->form->get('content')->render());
    }
}
