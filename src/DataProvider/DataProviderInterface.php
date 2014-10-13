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
namespace Vegas\Forms\DataProvider;

/**
 * This interface is intended to be used for creating dynamic data providers for multiple inputs.
 */
interface DataProviderInterface
{
    /**
     * Returns name of data provider which can be translated to be used for selection.
     */
    public function getName();
    
    /**
     * Return data for multiple input list purposes.
     * Result array is associative with standard key => value settings
     * @return array
     */
    public function getData();
    
    /**
     * Used to provide any additional settings needed.
     * @param array $options
     */
    public function setOptions(array $options);
}
