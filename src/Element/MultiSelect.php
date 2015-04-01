<?php
/**
 * This file is part of Vegas package
 *
 * Create multi select tag with otpions.
 * Example usage:
 * <code>
 * $multi = new MultiSelect('test', array(
 *     'one' => 'One',
 *     'two' => 'Two',
 *     'three' => '3'
 * ));
 * $this->add($multi);
 * </code>
 * 
 * @author Arkadiusz Ostrycharz <aostrycharz@amsterdam-standard.pl>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage http://vegas-cmf.github.io/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Vegas\Forms\Element;

use Vegas\Forms\Decorator;

class MultiSelect extends Select implements Decorator\DecoratedInterface
{
    use Decorator\DecoratedTrait;

    public function __construct($name, $options = null, $attributes = null)
    {
        $templatePath = implode(DIRECTORY_SEPARATOR, [dirname(__FILE__), 'MultiSelect', 'views', '']);
        $this->setDecorator(new Decorator($templatePath));
        $this->setAttribute('name', $name.'[]');

        parent::__construct($name, $options, $attributes);
    }
}
