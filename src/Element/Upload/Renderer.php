<?php
/**
 * This file is part of Vegas package
 *
 * @author Adrian Malik <arkadiusz.ostrycharz@gmail.com>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage https://bitbucket.org/amsdard/vegas-phalcon
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Vegas\Forms\Element\Upload;

use Phalcon\Forms\Element,
    Vegas\Forms\Element\Upload;

class Renderer
{
    private $upload;
    private $attributes = array();

    public function __construct(Upload $element, array $attributes = array()) {
        $this->upload = $element;
        $this->attributes = $attributes;
    }

    public function run()
    {
        return $this->wrapWithDecorator($this->render());
    }

    private function render()
    {
        $attributes = $this->upload->getAttributes();

        $file = new \Phalcon\Forms\Element\File($this->upload->getName());
        $file->setAttributes(array_merge($attributes, $this->upload->getUploadAttributes()));

        $label = 'Add file';

        $buttonLabels = $this->upload->getButtonLabels();

        if(isset($buttonLabels) && isset($buttonLabels['add'])) {
            $label = $buttonLabels['add'];
        }

        $file->setAttribute('data-button-add-label', $label);

        return $file->render();
    }

    private function wrapWithDecorator($html)
    {
        return sprintf($this->getDecorator(), $html);
    }

    private function getDecorator()
    {
        $baseElementsTemplates = '';
        $baseElements = $this->upload->getBaseElements();
        if(isset($baseElements)) {
            foreach($baseElements as $baseElement) {
                $baseElementsTemplates .= '<script id="' . $baseElement->getAttribute('data-template-id') . '" type="text/x-handlebars-template">';
                $baseElement->setName('[[' . $baseElement->getName() . ']]');
                $baseElementsTemplates .= $baseElement->render();
                $baseElementsTemplates .= '</script>';
            }
        }

        return
            '<div data-form-element-upload-wrapper="true">' .
                '%s' .
                '<div data-jq-upload-error></div>'.
                '<div data-jq-upload-preview></div>'.
                '<div data-templates>' .
                    $baseElementsTemplates .
                '</div>' .
            '</div>';
    }

}
