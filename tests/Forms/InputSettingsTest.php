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
use Vegas\Forms\Element\Browser;
use Vegas\Forms\Element\RichTextArea;
use Vegas\Forms\Form;
use Vegas\Forms\InputSettings;
use Vegas\Tests\Stub\NotDecoratedExampleForm;

class InputSettingsTest extends \PHPUnit_Framework_TestCase
{
    protected $di;

    public function setUp()
    {
        $this->di = DI::getDefault();
    }

    public function testEmptyDataProvider()
    {
        $inputSettings = new InputSettings();
        $inputSettings->initialize();

        $inputSettings->get(InputSettings::DATA_PARAM)->setDefault('');

        $result = $inputSettings->getDataFromProvider();
        $this->assertEmpty($result);
    }

    /**
     * @expectedException \Vegas\Forms\DataProvider\Exception\NotFoundException
     */
    public function testInvalidClassDataProvider()
    {
        $inputSettings = new InputSettings();
        $inputSettings->initialize();

        $inputSettings->get(InputSettings::DATA_PARAM)->setDefault('InvalidClassName_FOO_BAR');
        $inputSettings->getDataFromProvider();
    }
}
