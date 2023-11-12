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

use AA\PSModuleSetting\Setting\Configuration\OptionsResolver\AbstractSettingsResolver;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class responsible to define default value for PrestaShop configuration options
 */
class PrestaShopConfigurationOptionsResolver extends AbstractSettingsResolver
{
    public function resolve($parameters)
    {
        return $this->resolver->resolve($parameters);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $shopId=\Context::getContext()->shop->id;
        $resolver->setDefaults([
            'global' => false,
            'html' => false,
            'default' => false,
            'id_lang' => null,
        ]);
        $resolver->setDefault('id_shop', function (Options $options) use ($shopId) {
            if (true === $options['global']) {
                return 0;
            }

            return $shopId;
        });
        $resolver->setDefault('id_shop_group', function (Options $options) {
            if (true === $options['global']) {
                return 0;
            }

            return null;
        });
        $resolver->setAllowedTypes('global', 'bool');
        $resolver->setAllowedTypes('id_lang', ['null', 'int']);
        $resolver->setAllowedTypes('id_shop', ['null', 'int']);
        $resolver->setAllowedTypes('id_shop_group', ['null', 'int']);
        $resolver->setAllowedTypes('html', 'bool');
    }
}
