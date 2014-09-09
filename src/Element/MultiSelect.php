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
 * @homepage https://github.com/vegas-cmf
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Vegas\Forms\Element;

use Phalcon\Forms\Element\Hidden;
use Vegas\Forms\Element\Exception\InvalidAssetsManagerException;

class MultiSelect extends Select
{
    public function __construct($name, $options = null, $attributes = null)
    {
        $attributes['multiple'] = 'multiple';
        $attributes['data-vegas-multiselect'] = true;
        
        parent::__construct($name, $options, $attributes);
    }
    
    public function render($attributes = array())
    {
        $attributes['name'] = $this->getName().'[]';
        $hiddenField = new Hidden($attributes['name']);

        //$this->addAssets();
        return $hiddenField->render().parent::render($attributes);
    }

    /*private function addAssets()
    {
        if(!$this->assets) {
            throw new InvalidAssetsManagerException();
        }

        $this->assets->addJs('assets/vendor/multiselect/js/jquery.multi-select.js');
        $this->assets->addJs('assets/js/lib/vegas/ui/multiselect.js');
        $this->assets->addCss('assets/vendor/multiselect/css/multi-select.css');
    }*/
}
