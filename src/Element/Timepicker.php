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

use \Phalcon\Forms\Element\Text;

class Timepicker extends Text
{
    public function __construct($name, $attributes = null) {
        $attributes['vegas-timepicker'] = true;
        parent::__construct($name, $attributes);
    }

    /*private function addAssets()
    {
        if(!$this->assets) {
            throw new InvalidAssetsManagerException();
        }

        $this->assets->addCss('assets/vendor/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css');
        $this->assets->addJs('assets/vendor/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js');
        $this->assets->addJs('assets/vendor/eonasdan-bootstrap-datetimepicker/src/js/locales/bootstrap-datetimepicker.nl.js');
        $this->assets->addJs('assets/js/lib/vegas/ui/timepicker.js');
    }*/
}
