<?php
/**
 * This file is part of Vegas package
 *
 * @author Arkadiusz Ostrycharz <aostrycharz@amsterdam-standard.pl>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage https://github.com/vegas-cmf
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Vegas\Forms\Element;

use \Phalcon\Forms\Element\TextArea;

class RichTextArea extends TextArea
{
    /**
     * Constructs rich text area (ckeditor)
     *
     * @param string $name
     * @param null $attributes
     */
    public function __construct($name, $attributes = null)
    {
        $attributes['vegas-richtext'] = true;
        parent::__construct($name, $attributes);
    }
    
    /*private function addAssets()
    {
        if(!$this->assets) {
            throw new InvalidAssetsManagerException();
        }

        $this->assets->addJs('assets/vendor/ckeditor/ckeditor.js');
        $this->assets->addJs('assets/vendor/ckeditor/adapters/jquery.js');
        $this->assets->addJs('assets/js/lib/vegas/ui/richtext.js');
    }*/
}
