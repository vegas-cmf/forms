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
use Phalcon\Forms\Element\Select;
use Vegas\Forms\Element\MultiSelect;
use Vegas\Tests\Stub\Models\FakeModel;
use Vegas\Tests\Stub\Models\FakeVegasForm;

class MultiSelectTest extends \PHPUnit_Framework_TestCase
{
    protected $di;
    protected $form;
    protected $model;

    protected function setUp()
    {
        $this->di = DI::getDefault();
        $this->model = new FakeModel();
        $this->form = new FakeVegasForm();

        $content = new MultiSelect('select');
        $this->form->add($content);
    }

    public function testRender()
    {
        $options = array(
            'test1' => 'foo',
            'test2' => 'bar'
        );
        $this->form->get('select')->addOptions($options);

        $this->assertNull($this->form->get('select')->getDecorator()->getDI());

        try {
            $this->form->get('select')->renderDecorated();
            throw new \Exception('Not this exception.');
        } catch (\Exception $ex) {
            $this->assertInstanceOf('\Vegas\Forms\Decorator\Exception\DiNotSetException', $ex);
        }

        $this->form->get('select')->getDecorator()->setDI($this->di);
        $this->assertInstanceOf('\Phalcon\DI', $this->form->get('select')->getDecorator()->getDI());

        $select = new Select('select', $options, ['name' => 'select[]']);

        $htmlDecorated = <<<RENDER
<input type="hidden" name="select[]" />
<select id="select" name="select[]" multiple="multiple" data-vegas-multiselect>
    <option value="test1">foo</option>
    <option value="test2">bar</option>
</select>
RENDER;

        $this->assertEquals($select->render(['value' => 'test1']), $this->form->get('select')->render(['value' => 'test1']));
        $this->assertEquals('', $this->form->get('select')->renderDecorated());

        $this->form->get('select')->getDecorator()->setTemplateName('bootstrap');
        $this->assertEquals($htmlDecorated, $this->form->get('select')->renderDecorated());
    }
}
