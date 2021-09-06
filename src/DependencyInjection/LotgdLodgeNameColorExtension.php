<?php

/**
 * This file is part of "LoTGD Bundle Name Color".
 *
 * @see https://github.com/lotgd-core/lodge-name-color-bundle
 *
 * @license https://github.com/lotgd-core/lodge-name-color-bundle/blob/master/LICENSE.txt
 * @author IDMarinas
 *
 * @since 0.1.0
 */

namespace Lotgd\Bundle\LodgeNameColorBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class LotgdLodgeNameColorExtension extends ConfigurableExtension
{
    public function loadInternal(array $mergedConfig, ContainerBuilder $container)
    {
        $loader = new PhpFileLoader($container, new FileLocator(\dirname(__DIR__).'/Resources/config'));

        $loader->load('services.php');

        $container->setParameter('lotgd_bundle.lodge_name_color.cost.first', $mergedConfig['cost']['first']);
        $container->setParameter('lotgd_bundle.lodge_name_color.cost.other', $mergedConfig['cost']['other']);
        $container->setParameter('lotgd_bundle.lodge_name_color.allowed.bold', $mergedConfig['allowed']['bold']);
        $container->setParameter('lotgd_bundle.lodge_name_color.allowed.italic', $mergedConfig['allowed']['italic']);
        $container->setParameter('lotgd_bundle.lodge_name_color.allowed.colors', $mergedConfig['allowed']['colors']);
    }
}
