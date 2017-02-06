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
namespace Vegas\Tests\Stub;

use \Vegas\Forms\Form;


class NotDecoratedExampleForm extends Form
{
    public function initialize()
    {
        $fakeDecorator = new \stdClass();
        $field = new NotDecoratedExampleElement('fake_field');
        $field->setDecorator($fakeDecorator);
        $this->add($field);
    }


}
