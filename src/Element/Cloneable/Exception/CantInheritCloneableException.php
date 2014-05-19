<?php
/**
 * This file is part of Vegas package
 *
 * @author Arkadiusz Ostrycharz <arkadiusz.ostrycharz@gmail.com>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage https://bitbucket.org/amsdard/vegas-phalcon
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vegas\Forms\Element\Cloneable\Exception;

use Vegas\Forms\Element\Cloneable\Exception;

class CantInheritCloneableException extends Exception
{
    protected $message = 'Cloneable element can not be other cloneable element base.';
}
