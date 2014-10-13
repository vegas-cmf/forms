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
namespace Vegas\Forms\Builder;

use Vegas\Forms\BuilderAbstract;
use Vegas\Forms\InputSettings,
    Phalcon\Forms\Element\Text as TextInput;

/**
 * Class Text
 * @package Vegas\Forms\Builder
 */
class Text extends BuilderAbstract
{
    public function setElement()
    {
        $name = $this->settings->getValue(InputSettings::IDENTIFIER_PARAM) ? $this->settings->getValue(InputSettings::IDENTIFIER_PARAM) : preg_replace('/.*\\\/', '', get_class($this)) . self::NAME_SEPARATOR . mt_rand();
        $this->element = new TextInput($name);
    }

}
