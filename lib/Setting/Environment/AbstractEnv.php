<?php
/*
 * MIT License
 *
 * Copyright (c) 2023 Anis Ajengui
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 */
namespace AA\PSModuleSetting\Setting\Environment;

/**
 * Get the current environment used: prod or test // sandbox or live
 */
abstract class AbstractEnv implements EnvProviderInterface
{
    /**
     * Const that define all environment possible to use.
     * Top of the list are taken in first if they exist in the project.
     * eg: If .env.test is present in the module it will be loaded, if not present
     * we try to load the next one etc ...
     *
     * @var array
     */
    const FILE_ENV_LIST = [
        'test' => '.env.test',
        'prod' => '.env',
    ];

    /**
     * Environment name: can be 'prod' or 'test'
     *
     * @var string
     */
    protected $name;

    /**
     * Environment mode: can be 'live' or 'sandbox'
     *
     * @var string
     */
    protected $mode;
    /**
     * @var string
     */
    protected $envPath;
    /**
     * @var array
     */
    private array $envVariables;

    public function __construct($path)
    {
        foreach (self::FILE_ENV_LIST as $env => $fileName) {
            if (!file_exists($path . $fileName)) {
                continue;
            }
            if(!empty($path)){
                $envLoader = new EnvLoader();
                $envVariables=$envLoader->load($path . $fileName, false);
                $this->setEnvVariables($envVariables);

                $this->setName($env);
                break;
            }

        }
    }

    /**
     * getter for name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * getter for mode
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * setter for name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * setter for mode
     *
     * @param string $mode
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function get($name)
    {
        if (isset($_ENV[$name])) {
            return $_ENV[$name];
        }

        if (isset($_SERVER[$name])) {
            return $_SERVER[$name];
        }

        return getenv($name);
    }

    /**
     * @return string
     */
    private function getEnvPath()
    {
        return $this->envPath;
    }

    /**
     * @param $envPath
     * @return $this
     */
    public function setEnvPath($envPath)
    {
        $this->envPath = $envPath;
        return $this;
    }

    /**
     * @param array $envVariables
     * @return $this
     */
    private function setEnvVariables(array $envVariables)
    {
         $this->envVariables=$envVariables;
         return $this;
    }
    public function getEnvVariables()
    {
        return $this->envVariables;
    }

    public function getAll()
    {
        $vars = [];
        $envVariables = $this->getEnvVariables();
        if (!empty($envVariables)) {
            foreach ($envVariables as $variable) {
                $vars[$variable] = $this->get($variable);
            }
        }
        return $vars;
    }
}
