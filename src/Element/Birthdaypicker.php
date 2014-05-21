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

use \Vegas\Forms\Element\Exception\InvalidAssetsManagerException;
use \Phalcon\Forms\Element\Text;

class Birthdaypicker extends Text implements AssetsInjectableInterface
{
    private $assets;
    
    public function __construct($name, $attributes = null)
    {
        $attributes['vegas-datepicker'] = true;
        $this->addFilter('dateToArray');
        
        parent::__construct($name, $attributes);
    }
    
    public function render($attributes = null)
    {
        $this->addAssets();
        return parent::render($attributes);
    }
    
    private function addAssets()
    {
        if(!$this->assets) {
            throw new InvalidAssetsManagerException();
        }
        
        $this->assets->addCss('assets/vendor/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css');
        $this->assets->addJs('assets/vendor/moment/min/moment.min.js');
        $this->assets->addJs('assets/vendor/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js');
        $this->assets->addJs('assets/js/lib/vegas/ui/datepicker.js');
    }
    
    public function getAssetsManager()
    {
        return $this->assets;
    }

    public function setAssetsManager(\Phalcon\Assets\Manager $assets)
    {
        $this->assets = $assets;
        
        return $this;
    }
    
    public function getValue()
    {
        $value = parent::getValue();
        
        if (!empty($value) && is_array($value)) {
            $value = implode('-', $value);
        }
        
        return $value;
    }
}
