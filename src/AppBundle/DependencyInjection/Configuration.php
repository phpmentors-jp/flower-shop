<?php

namespace AppBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * セマンティックコンフィギュレーションのグラマー定義
 */
class Configuration implements ConfigurationInterface
{
    /**
     * ツリーを定義する
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('app');
        return $treeBuilder;
    }
}
