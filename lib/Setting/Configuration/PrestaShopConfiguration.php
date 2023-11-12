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

namespace AA\PSModuleSetting\Setting\Configuration;

use Configuration;


/**
 * Class responsible to manage PrestaShop configuration
 */
class PrestaShopConfiguration
{
    /**
     * @var PrestaShopConfigurationOptionsResolver
     */
    private $optionsResolver;

    /**
     * @param PrestaShopConfigurationOptionsResolver $optionsResolver
     */
    public function __construct(PrestaShopConfigurationOptionsResolver $optionsResolver)
    {
        $this->optionsResolver = $optionsResolver;
    }

    /**
     * @param string $key
     * @param array $options Options
     *
     * @return bool
     */
    public function has($key, array $options = [])
    {
        $settings = $this->optionsResolver->resolve($options);

        return (bool) Configuration::hasKey(
            $key,
            $settings['id_lang'],
            $settings['id_shop_group'],
            $settings['id_shop']
        );
    }

    /**
     * @param string $key
     * @param array $options Options
     *
     * @return mixed
     */
    public function get($key, array $options = [])
    {
        $settings = $this->optionsResolver->resolve($options);

        $value = Configuration::get(
            $key,
            $settings['id_lang'],
            $settings['id_shop_group'],
            $settings['id_shop']
        );

        if (empty($value)) {
            return $settings['default'];
        }

        return $value;
    }

    /**
     * Set configuration value.
     *
     * @param string $key
     * @param mixed $value
     * @param array $options Options
     *
     * @return $this
     *
     * @throws \Exception
     */
    public function set($key, $value, array $options = [])
    {
        $settings = $this->optionsResolver->resolve($options);

        $success = (bool) Configuration::updateValue(
            $key,
            $value,
            $settings['html'],
            $settings['id_shop_group'],
            $settings['id_shop']
        );

        if (false === $success) {
            throw new \Exception(sprintf('Could not set key %s in PrestaShop configuration', $key));
        }

        return $this;
    }

    /**
     * @param string $key
     *
     * @return $this
     *
     * @throws \Exception
     */
    public function remove($key)
    {
        $success = (bool) Configuration::deleteByName($key);

        if (false === $success) {
            throw new \Exception(sprintf('Could not remove key %s from PrestaShop configuration', $key));
        }

        return $this;
    }
}
