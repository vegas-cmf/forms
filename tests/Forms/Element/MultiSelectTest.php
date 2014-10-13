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
use Vegas\Forms\Element\Select;
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

        $testElement = new Select('select', $options, ['name' => 'select[]']);

        $this->form->get('select')->addOptions($options);

        $this->assertEquals(
            $testElement->render(),
            $this->form->get('select')->renderDecorated()
        );

        $this->form->get('select')->getDecorator()->setTemplateName('jquery');
        $this->assertNull($this->form->get('select')->getDecorator()->getDI());

        try {
            $this->form->get('select')->renderDecorated();
            throw new \Exception('Not this exception.');
        } catch (\Exception $ex) {
            $this->assertInstanceOf('\Vegas\Forms\Decorator\Exception\DiNotSetException', $ex);
        }

        $this->form->get('select')->getDecorator()->setDI($this->di);
        $this->assertInstanceOf('\Phalcon\DI', $this->form->get('select')->getDecorator()->getDI());

        $htmlDecorated = <<<RENDER
<input type="hidden" name="select[]" />
<select id="select" name="select[]" multiple="multiple" data-vegas-multiselect>
    <option value="test1">foo</option>
    <option value="test2">bar</option>
</select>
RENDER;

        $this->assertEquals($testElement->render(['value' => 'test1']), $this->form->get('select')->render(['value' => 'test1']));
        $this->assertEquals($htmlDecorated, $this->form->get('select')->renderDecorated());
    }
}
