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
namespace Vegas\Forms\Element;

use Vegas\Forms\DecoratedTrait;
use Vegas\Forms\Decorator;

/**
 * Class Browser
 * @package Vegas\Forms\Element
 */
class Browser extends \Phalcon\Forms\Element\Text
{
    use DecoratedTrait;
    
    /**
     * Constructs rich text area (ckeditor)
     *
     * @param string $name
     * @param null $attributes
     */
    public function __construct($name, $attributes = null)
    {
        $templatePath = implode(DIRECTORY_SEPARATOR, [dirname(__FILE__), 'Browser', 'views', '']);
        $this->setDecorator(new Decorator($templatePath));
        parent::__construct($name, $attributes);
    }
}
