<?php

namespace h4cc\AliceFixturesBundle\DependencyInjection\Compiler;

use Nelmio\Alice\Instances\Processor\Methods\Reference;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AliceMethodsCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $factoryDefinition = $container->findDefinition('h4cc_alice_fixtures.loader.factory');

        $factoryDefinition->setArguments(
            [
                $this->getTaggedServiceReferences($container, 'h4cc_alice_fixtures.provider'),
                $this->getTaggedServiceReferences($container, 'h4cc_alice_fixtures.processor'),
                $this->getTaggedServiceReferences($container, 'h4cc_alice_fixtures.builder'),
                $this->getTaggedServiceReferences($container, 'h4cc_alice_fixtures.instantiator'),
                $this->getTaggedServiceReferences($container, 'h4cc_alice_fixtures.parser'),
                $this->getTaggedServiceReferences($container, 'h4cc_alice_fixtures.populator')
            ]
        );
    }

    /**
     * @param ContainerBuilder $container
     * @param string $tagName
     * @return Reference[] References to found tagged services
     */
    protected function getTaggedServiceReferences(ContainerBuilder $container, $tagName)
    {
        $references = [];
        foreach (array_keys($container->findTaggedServiceIds($tagName)) as $id) {
            $references[] = new Reference($id);
        }
        return $references;
    }
}
