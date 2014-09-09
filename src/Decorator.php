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
use Vegas\Forms\Decorator\DecoratorInterface;
use Vegas\Forms\Decorator\Exception\DiNotSetException;
use Vegas\Forms\Decorator\Exception\InvalidAssetsManagerException;
use Vegas\Forms\Decorator\Exception\ViewNotSetException;

class Decorator implements DecoratorInterface
{
    protected $templateName = 'base';
    protected $templatePath;
    protected $di;

    public function __construct($path = null)
    {
        if ($path) {
            $this->templatePath = $path;
        }
    }

    public function render($attributes = array())
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

        $partial = $this->generatePartial($attributes);

        return $partial;
    }

    private function generatePartial(array $attributes)
    {
        $view = $this->di->get('view');

        if ($this->templatePath) {
            $view->setPartialsDir($this->templatePath);
        }

        ob_start();

        $view->partial($this->templateName, ['attributes' => $attributes]);
        $partial = ob_get_contents();

        ob_end_clean();

        return $partial;
    }

    public function setTemplateName($name)
    {
        $this->templateName = (string)$name;
        return $this;
    }

    public function setTemplatePath($path)
    {
        $this->templatePath = (string)$path;
        return $this;
    }

    public function setDI(DiInterface $di)
    {
        $this->di = $di;
        return $this;
    }

    public function getDI()
    {
        return $this->di;
    }
}
