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
        $this->form->get('select')->addOptions(array(
            'test1' => 'foo',
            'test2' => 'bar'
        ));

        /*$this->assertNull($this->form->get('select')->getAssetsManager());

        try {
            $this->form->get('select')->render();
        } catch (\Exception $ex) {
            $this->assertInstanceOf('\Vegas\Forms\Element\Exception\InvalidAssetsManagerException', $ex);
        }

        $this->form->get('select')->setAssetsManager($this->di->get('assets'));

        $this->assertInstanceOf('\Phalcon\Assets\Manager', $this->form->get('select')->getAssetsManager());
*/
        $this->form->get('select')->addOptions(array(
            'test3' => 'baz'
        ));

        $html = <<<RENDERED
<input type="hidden" name="select[]" /><select id="select" name="select[]" multiple="multiple" data-vegas-multiselect="1">
	<option value="test1">foo</option>
	<option value="test2">bar</option>
	<option value="test3">baz</option>
</select>
RENDERED;

        $this->assertEquals($html, $this->form->get('select')->render());
    }
}
