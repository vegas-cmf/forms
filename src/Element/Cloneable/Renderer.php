<?php
/**
 * This file is part of Vegas package
 * 
 * Cloneable element is representation of dynamic data set.
 * 
 * @author Arkadiusz Ostrycharz <arkadiusz.ostrycharz@gmail.com>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage https://bitbucket.org/amsdard/vegas-phalcon
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Vegas\Forms\Element\Cloneable;

use Vegas\Forms\Element\Cloneable;

class Renderer
{
    private $decorator = '<div vegas-cloneable="1">%s</div>';
    private $cloneable;
    private $attributes;

    public function __construct(Cloneable $cloneable, $attributes = array()) {
        $this->cloneable = $cloneable;
        $this->attributes = $attributes;
    }
    
    public function run()
    {
        $html = '';
        foreach ($this->cloneable->getRows() As $row) {
            $html.= $this->renderRow($row);
        }
        
        return $this->wrapWithDecorator($html);
    }
    
    private function renderRow(Cloneable\Row $row)
    {
        $html = '';
        
        foreach ($row->getElements() As $element) {
            $html.= $element->render();
        }

        return '<fieldset'.$this->printAttributes().'>'.$html.'</fieldset>';
    }
    
    private function printAttributes()
    {
        $attributes = '';
        
        foreach ($this->attributes As $name => $attribute) {
            $attributes.= ' '.$name.'="'.$attribute.'"';
        }
        
        return $attributes;
    }
    
    private function wrapWithDecorator($html)
    {
        return sprintf($this->decorator, $html);
    }
}
