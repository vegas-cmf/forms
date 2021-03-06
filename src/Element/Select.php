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
namespace Vegas\Forms\Element;

use Vegas\Forms\Decorator\UnDecoratedTrait;

class Select extends \Phalcon\Forms\Element\Select
{
    use UnDecoratedTrait;

    /**
     * Allows to add multiple options at once when providing just one array.
     * @param array $options array of key => value for each option
     * @return $this
     */
    public function addOptions(array $options)
    {
        $existingOptions = $this->getOptions();
        $newOptions = empty($existingOptions) ? $options : array_merge((array)$existingOptions, $options);
        $this->setOptions($newOptions);
        return $this;
    }
}
