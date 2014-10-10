<?php
/**
 * This file is part of Vegas package
 *
 * @author Arkadiusz Ostrycharz <aostrycharz@amsterdam-standard.pl>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage https://github.com/vegas-cmf
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Vegas\Forms;

use Phalcon\DI\FactoryDefault;
use Phalcon\DiInterface;
use Phalcon\Forms\ElementInterface;
use Vegas\Forms\Decorator\DecoratorInterface;
use Vegas\Forms\Decorator\Exception\DiNotSetException;
use Vegas\Forms\Decorator\Exception\InvalidAssetsManagerException;
use Vegas\Forms\Decorator\Exception\ViewNotSetException;
use Phalcon\Mvc\View;

class Decorator implements DecoratorInterface
{
    protected $templateName;
    protected $templatePath;
    protected $di;

    /**
     * Create object with default $path.
     *
     * @param null $path
     */
    public function __construct($path = null)
    {
        if ($path) {
            $this->templatePath = $path;
        }
    }

    /**
     * Render form element with current templatePath and templateName.
     *
     * @param ElementInterface $formElement
     * @param string $value
     * @param array $attributes
     * @return string
     * @throws Decorator\Exception\DiNotSetException
     * @throws Decorator\Exception\InvalidAssetsManagerException
     * @throws Decorator\Exception\ViewNotSetException
     */
    public function render(ElementInterface $formElement, $value = '', $attributes = array())
    {
        if (!($this->di instanceof DiInterface)) {
            throw new DiNotSetException();
        }

        if (!$this->di->has('view')) {
            throw new ViewNotSetException();
        }

        if (!$this->di->has('assets')) {
            throw new InvalidAssetsManagerException();
        }

        $partial = $this->generatePartial($formElement, $value, $attributes);

        return $partial;
    }

    /**
     * @param ElementInterface $formElement
     * @param string $value
     * @param array $attributes
     * @return mixed
     */
    private function generatePartial(ElementInterface $formElement, $value = '', array $attributes)
    {
        $view = $this->di->get('view');

        if ($this->templatePath) {
            $view->setViewsDir($this->templatePath);
        }

        return $view->getRender('', $this->templateName, [
            'attributes' => $attributes,
            'element' => $formElement,
            'value' => $value
        ], function ($view) {
            $view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        });
    }

    /**
     * Set template name for decorator.
     *
     * @param $name
     * @return $this
     */
    public function setTemplateName($name)
    {
        $this->templateName = (string)$name;
        return $this;
    }

    /**
     * Set template path for decorator.
     *
     * @param $path
     * @return $this
     */
    public function setTemplatePath($path)
    {
        $this->templatePath = (string)$path;
        return $this;
    }

    /**
     * @param DiInterface $di
     * @return $this
     */
    public function setDI($di)
    {
        $this->di = $di;
        return $this;
    }

    /**
     * @return DiInterface
     */
    public function getDI()
    {
        return $this->di;
    }
}
