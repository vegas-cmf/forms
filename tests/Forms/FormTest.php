<?php
/**
 * This file is part of Vegas package
 *
 * @author Radosław Fąfara <radek@archdevil.pl>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage http://vegas-cmf.github.io/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Vegas\Tests\Forms;
use Phalcon\Forms\Element\Text;
use Vegas\Validation\Validator\PresenceOf;
use Vegas\Forms\Element\Cloneable;
use Vegas\Forms\Form;
use Vegas\Tests\Stub\Models\FakeModel;

/**
 * Main test case.
 */
class FormTest extends \PHPUnit_Framework_TestCase
{
    public function testArrayedValues()
    {
        $model = new FakeModel();

        $values = array(
            'test1' => array(
                2 => 'foo',
                3 => 'bar'
            ),
            'test2' => array(
                'en' => 'baz',
                'nl' => 123.4
            ),
            'test3' => array(
                array(
                    0 => 'doubleArrayed',
                    1 => array(
                        0 => 'tripleArrayed'
                    )
                )
            ),
            'not_in_form' => array(
                'some_value'
            )
        );

        $form = new Form();

        $form->add(new Text('test1[]'));
        $form->add(new Text('test1[]'));

        $text = new Text('test2[en]');
        $text->addValidator(new PresenceOf());

        $form->add($text);
        $form->add(new Text('test2[nl]'));

        $form->add(new Text('test3[0][]'));
        $form->add(new Text('test3[0][1][]'));

        $form->bind($values, $model);

        $this->assertTrue($form->isValid());
        $this->assertTrue(!isset($model->not_in_form));

        $this->assertEquals($model->test1[0], $form->getValue('test1[2]'));
        $this->assertEquals($model->test1[1], $form->getValue('test1[3]'));

        $this->assertNull($form->getValue('test1[]'));
        $this->assertNull($form->getValue('test1[1]'));

        $this->assertEquals($model->test2['en'], $form->getValue('test2[en]'));
        $this->assertEquals($model->test2['nl'], $form->getValue('test2[nl]'));

        $this->assertNull($form->getValue('test2[en][notexsiting]'));

        $this->assertEquals($model->test3[0][0], $form->getValue('test3[0][0]'));
        $this->assertEquals($model->test3[0][1][0], $form->getValue('test3[0][1][0]'));
    }
}
