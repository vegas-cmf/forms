<?php
/**
 * This file is part of Vegas package
 *
 * @author Adrian Malik <adrian.malik.89@gmail.com>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage http://vegas-cmf.github.io/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vegas\Forms\Decorator\Exception;

use \Vegas\Forms\Decorator\Exception as ElementException;

class InvalidAssetsManagerException extends ElementException
{
    protected $message = 'Invalid assets manager';
}
