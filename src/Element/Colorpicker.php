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

class Colorpicker extends \Phalcon\Forms\Element\Text implements AssetsInjectableInterface
{
    private $assets;
    
    public function __construct($name, $attributes = null) {
        $attributes['vegas-colorpicker'] = true;
        parent::__construct($name, $attributes);
    }
    
    public function render($attributes = null) {
        $this->addAssets();
        return parent::render($attributes);
    }
    
    private function addAssets()
    {
        if(!$this->assets) {
            throw new InvalidAssetsManagerException();
        }
        
        $this->assets->addCss('assets/vendor/mjaalnir-bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css');
        $this->assets->addJs('assets/vendor/mjaalnir-bootstrap-colorpicker/dist/js/bootstrap-colorpicker.js');
        $this->assets->addJs('assets/js/lib/vegas/ui/colorpicker.js');
    }
    
    public function getAssetsManager()
    {
        return $this->assets;
    }

    public function setAssetsManager(\Vegas\Assets\Manager $assets)
    {
        $this->assets = $assets;
        
        return $this;
    }
}
