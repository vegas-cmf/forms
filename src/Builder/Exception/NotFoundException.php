<?php
/**
 * This file is part of Vegas package
 *
 * @author Radosław Fąfara <radek@archdevil.pl>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage https://github.com/vegas-cmf
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vegas\Forms\Builder\Exception;

use \Vegas\Forms\Builder\Exception as BuilderException;

/**
 *
 * @package Vegas\Forms\Exception
 */
class NotFoundException extends BuilderException
{
    protected $message = 'Input builder not found';
}
