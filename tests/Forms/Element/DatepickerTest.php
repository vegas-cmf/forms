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
use Vegas\Forms\Element\Cloneable;
use Vegas\Tests\Stub\Models\FakeForm;
use Vegas\Tests\Stub\Models\FakeModel;

class DatepickerTest extends \PHPUnit_Framework_TestCase
{
    protected $di;
    protected $form;
    
    protected function setUp()
    {
        $this->di = DI::getDefault();
        $this->form = new FakeForm();
    }


}
