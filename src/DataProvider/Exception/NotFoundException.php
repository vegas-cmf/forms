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

namespace Vegas\Forms\DataProvider\Exception;

use \Vegas\Forms\Exception as FormsException;

/**
 *
 * @package Vegas\Forms\DataProvider\Exception
 */
class NotFoundException extends FormsException
{
    protected $message = 'Input data provider not found';
}
