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
namespace Vegas\Forms\Element\Cloneable\Validation;

use Phalcon\Validation\Validator;
use Phalcon\Validation\Message;

class Extender extends Validator
{
    private $validator;
    private $attribute;
    private $messages;

    public function validate($validator, $attribute)
    {
        $this->validator = $validator;
        $this->attribute = $attribute;
        $this->messages = array();

        $value = $validator->getValue($attribute);

        if (is_array($value) && $this->getOption('cloneable') instanceof \Vegas\Forms\Element\Cloneable) {
            return $this->validateAll($value);
        }

        $this->appendSingleMessage('Validator suitable only for Cloneable element.');
        return false;
    }

    protected function validateAll(array $values)
    {
        $valid = true;

        foreach ($this->getOption('cloneable')->getRows() As $key => $row) {
            if (isset($values[$key])) {
                $this->messages[$key] = $this->validateRowElements($row, $values[$key]);
                $valid = $valid && !$this->messages[$key]->count();
            }
        }

        if (!$valid) {
            $this->appendAllMessages();
        }

        return $valid;
    }

    /**
     * @param \Vegas\Forms\Element\Cloneable\Row $row
     * @param array $values
     * @return \Phalcon\Validation\Message\Group
     */
    protected function validateRowElements(\Vegas\Forms\Element\Cloneable\Row $row, $values)
    {
        $messagesGroup = new \Phalcon\Validation\Message\Group();
        $validation = new \Phalcon\Validation();

        foreach ($row->getElements() As $key => $element) {
            if (!$element->getValidators()) {
                break;
            }

            foreach ($element->getValidators() As $validator) {
                $validation->add($key, $validator);
            }
        }

        if (count($validation->getValidators())) {
            $messagesGroup = $validation->validate($values);
        }

        return $messagesGroup;
    }

    private function appendSingleMessage($message)
    {
        $this->validator->appendMessage(new Message($message, $this->attribute, 'Cloneable'));
    }

    private function appendAllMessages()
    {
        foreach ($this->messages As $rowNb => $messageGroup) {
            $this->appendRowMessages($rowNb, $messageGroup);
        }
    }

    /**
     * @param int $rowNb
     * @param \Phalcon\Validation\Message\Group $messageGroup
     */
    private function appendRowMessages($rowNb, \Phalcon\Validation\Message\Group $messageGroup)
    {
        foreach ($messageGroup As $message) {
            $this->appendSingleMessage('<p>Row '.($rowNb+1).': '.$message->getMessage().'</p>');
        }
    }
}
