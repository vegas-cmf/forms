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

class Browser extends Text implements AssetsInjectableInterface
{
    private $assets;
    
    /**
     * Constructs rich text area (ckeditor)
     *
     * @param string $name
     * @param null $attributes
     */
    public function __construct($name, $attributes = null)
    {
        $attributes['vegas-browser'] = true;
        parent::__construct($name, $attributes);
    }
    
    public function render($attributes = null)
    {
        $this->addAssets();
        
        $input = parent::render($attributes);
        
        $html = '<div class="input-group browser-wrapper">
                    ' . $input . '
                    <div class="input-group-btn">
                        <a class="btn btn-primary btn-browse">Browse</a>
                    </div>
                </div>';
        return $html;
    }
    
    private function addAssets()
    {
        if(!$this->assets) {
            throw new InvalidAssetsManagerException();
        }
        $this->assets->addJs('assets/js/lib/vegas/ui/browser.js');
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
}
