<?php
/**
 * This file is part of Vegas package
 *
 * @author Mateusz Aniołek <dev@mateusz-aniolek.com>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage https://bitbucket.org/amsdard/vegas-phalcon
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vegas\Forms;

/**
 * Interface BuilderInterface
 * @package Vegas\Forms
 */
interface BuilderInterface
{
    /**
     * Method sets form element. For case when name of element isn't given (by user or database), random name should be
     * assigned. e.g. uniqid(). New instance of element should be set into private field 'element'
     */
    function setElement();

    /**
     * Method assign validator class into element
     */
    function setValidator();

    /**
     * Method sets label of element
     */
    function setLabel();

    /**
     * Method sets default value of element
     */
    function setDefault();

    /**
     * Method sets placeholder attribute for element
     */
    function setAttributes();

    /**
     * Method sets additional options for edit form
     */
    function setAdditionalOptions();
}