<?php
/**
 * This file is part of Vegas package
 *
 * @author Radosław Fąfara <radek@archdevil.pl>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage https://bitbucket.org/amsdard/vegas-phalcon
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Vegas\Forms\Element;

class Select extends \Phalcon\Forms\Element\Select
{
    /**
     * Allows to add multiple options at once when providing just one array.
     * @param array $options array of key => value for each option
     * @return $this
     */
    public function addOptions(array $options)
    {
        $existingOptions = $this->getOptions();
        $newOptions = empty($existingOptions) ? $options : array_merge($existingOptions, $options);
        $this->setOptions($newOptions);
        return $this;
    }
}
