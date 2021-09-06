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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('lotgd_lodge_name_color');

        $treeBuilder->getRootNode()
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('cost')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->integerNode('first')
                            ->min(0)
                            ->defaultValue(300)
                            ->info('How many points will the first color change cost?')
                        ->end()
                        ->integerNode('other')
                            ->min(0)
                            ->defaultValue(25)
                            ->info('How many points will subsequent color changes cost?')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('allowed')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->integerNode('colors')
                            ->min(0)
                            ->max(100)
                            ->defaultValue(10)
                            ->info('How many color changes are allowed in names?')
                        ->end()
                        ->booleanNode('bold')
                            ->defaultValue(true)
                            ->info('Can use bold for title changes?')
                        ->end()
                        ->booleanNode('italic')
                            ->defaultValue(true)
                            ->info('Can use italic for title changes?')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
