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
namespace Vegas\Tests\Forms;

use Phalcon\DI;
use Vegas\Forms\Decorator;
use Vegas\Forms\Element\RichTextArea;

class DecoratorTest extends \PHPUnit_Framework_TestCase
{
    protected $di;
    protected $field;

    public function setUp()
    {
        $this->di = DI::getDefault();
        $this->field = new RichTextArea('field');
    }

    public function testInvalid()
    {
        $decorator = new Decorator('/test/path', 'jquery');

        $this->assertNull($decorator->getDI());

        try {
            $decorator->render($this->field);
            throw new \Exception('Not this exception.');
        } catch (\Exception $ex) {
            $this->assertInstanceOf('\Vegas\Forms\Decorator\Exception\DiNotSetException', $ex);
        }

        $di = new DI();
        $decorator->setDI($di);

        try {
            $decorator->render($this->field);
            throw new \Exception('Not this exception.');
        } catch (\Exception $ex) {
            $this->assertInstanceOf('Vegas\Forms\Decorator\Exception\ViewNotSetException', $ex);
        }

        $di->set('view', $this->di->get('view'));
        $decorator->setDI($di);

        try {
            $decorator->render($this->field);
            throw new \Exception('Not this exception.');
        } catch (\Exception $ex) {
            $this->assertInstanceOf('Vegas\Forms\Decorator\Exception\InvalidAssetsManagerException', $ex);
        }

        $templatePath = implode(DIRECTORY_SEPARATOR, [dirname(__FILE__), 'Element', 'views', '']);
        $decorator = new Decorator($templatePath, 'notExistingTemplateName');
        $decorator->setDI($this->di);

        $this->assertEquals('', $decorator->render($this->field));
    }

    public function testValid()
    {
        $templatePath = implode(DIRECTORY_SEPARATOR, [dirname(__FILE__), 'Element', 'views', '']);
        $decorator = new Decorator($templatePath, 'test');
        $decorator->setDI($this->di);

        $this->assertInstanceOf('\Phalcon\DI', $decorator->getDI());
        $this->assertEmpty($decorator->render($this->field));

        $decorator->addVariable('testVariable', 'bazbar');
        $this->assertEquals('bazbar', $decorator->render($this->field));

        $decorator->setVariables(['testVariable' => 'bazbar']);
        $this->assertEquals('bazbar', $decorator->render($this->field));

        $templatePath = implode(DIRECTORY_SEPARATOR, [dirname(dirname(__DIR__)), 'src', 'Element', 'RichTextArea', 'views', '']);
        $decorator->setTemplatePath($templatePath);

        $this->assertEmpty($decorator->render($this->field));

        $decorator->setTemplateName('jquery');

        $html = '<textarea vegas-richtext>';

        $this->assertContains($html, $decorator->render($this->field));
        $this->assertContains('test1234foobar', $decorator->render($this->field, 'test1234foobar'));

        $html = '<textarea foo="bar" vegas-richtext>';

        $this->assertContains($html, $decorator->render($this->field, null, ['foo' => 'bar']));
        $this->assertContains('test1234foobar', $decorator->render($this->field, 'test1234foobar', ['foo' => 'bar']));

        $this->di->get('config')->forms->templates->default_name = 'jquery';
        $decorator = new Decorator($templatePath);
        $decorator->setDI($this->di);

        $html = '<textarea vegas-richtext>';

        $this->assertContains($html, $decorator->render($this->field));
        $this->assertContains('test1234foobar', $decorator->render($this->field, 'test1234foobar'));

        // reset config to previous state
        $this->di->get('config')->offsetUnset('forms');
    }
}
