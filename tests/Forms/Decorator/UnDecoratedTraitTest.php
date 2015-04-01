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
namespace Vegas\Tests\Forms\Decorator;

use Phalcon\Forms\Element\Text;

class UnDecoratedTraitTest extends \PHPUnit_Framework_TestCase
{
    public function testRenderCompare()
    {
        $phalconText = new Text('test');
        $vegasText = new \Vegas\Forms\Element\Text('test');

        $this->assertEquals($phalconText->render(), $vegasText->renderDecorated());
    }

}
