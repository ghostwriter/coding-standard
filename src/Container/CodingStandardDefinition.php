<?php

declare(strict_types=1);

namespace Ghostwriter\CodingStandard\Container;

use Ghostwriter\CodingStandard\Container\Ghostwriter\Config\ConfigurationExtension;
use Ghostwriter\Config\Configuration;
use Ghostwriter\Config\Interface\ConfigurationInterface;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\Service\DefinitionInterface;
use Override;
use Throwable;

/**
 * @see CodingStandardDefinitionTest
 */
final readonly class CodingStandardDefinition implements DefinitionInterface
{
    /** @throws Throwable */
    #[Override]
    public function __invoke(ContainerInterface $container): void
    {
        $container->alias(Configuration::class, ConfigurationInterface::class);
        $container->extend(ConfigurationInterface::class, ConfigurationExtension::class);

        $configuration = $container->get(ConfigurationInterface::class);

        $containerConfiguration = $configuration->wrap('ghostwriter/container');

        foreach ($containerConfiguration->get('alias', []) as $alias => $service) {
            $container->alias($service, $alias);
        }

        foreach ($containerConfiguration->get('define', []) as $definition) {
            $container->define($definition);
        }

        foreach ($containerConfiguration->get('extend', []) as $service => $extensions) {
            foreach ($extensions as $extension) {
                $container->extend($service, $extension);
            }
        }

        foreach ($containerConfiguration->get('factory', []) as $service => $factory) {
            $container->factory($service, $factory);
        }
    }
}
