<?php
/**
 * This file is part of Vegas package
 *
 * @author Mateusz Aniołek <dev@mateusz-aniolek.com>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage http://vegas-cmf.github.io/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vegas\Forms\Builder;

use Vegas\Forms\BuilderAbstract;
use Vegas\Forms\InputSettings,
    Vegas\Forms\Element\RichTextArea as RichTextAreaInput;

/**
 * Class RichTextArea
 * @package Vegas\Forms\Builder
 */
class RichTextArea extends BuilderAbstract
{
    public function setElement()
    {
        $name = $this->settings->getValue(InputSettings::IDENTIFIER_PARAM) ? $this->settings->getValue(InputSettings::IDENTIFIER_PARAM) : preg_replace('/.*\\\/', '', get_class($this)) . self::NAME_SEPARATOR . mt_rand();
        $this->element = (new RichTextAreaInput($name));
    }
}
