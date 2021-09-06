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

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Lotgd\Bundle\LodgeNameColorBundle\Controller\LodgeNameColorController;
use Lotgd\Bundle\LodgeNameColorBundle\EventSubscriber\LodgeNameChangeSubscriber;
use Lotgd\Bundle\LodgeNameColorBundle\Form\NameColorType;
use Lotgd\Core\Http\Response;
use Lotgd\Core\Navigation\Navigation;

return static function (ContainerConfigurator $container)
{
    $container->services()
        //-- Controllers
        ->set(LodgeNameColorController::class)
            ->lazy()
            ->args([
                new ReferenceConfigurator('lotgd.core.tools'),
                new ReferenceConfigurator(Response::class),
                new ReferenceConfigurator(Navigation::class),
                new ReferenceConfigurator('translator'),
                new ReferenceConfigurator('lotgd.core.sanitize'),
                new ReferenceConfigurator('parameter_bag'),
                new ReferenceConfigurator('event_dispatcher'),
                new ReferenceConfigurator('lotgd.core.log')
            ])
            ->call('setContainer', [
                new ReferenceConfigurator('service_container')
            ])
            ->tag('controller.service_arguments')

        //-- Event Subscribers
        ->set(LodgeNameChangeSubscriber::class)
            ->args([
                new ReferenceConfigurator('parameter_bag'),
                new ReferenceConfigurator(Navigation::class)
                ])
            ->tag('kernel.event_subscriber')

        //-- Forms
        ->set(NameColorType::class)
            ->lazy()
            ->args([
                new ReferenceConfigurator('parameter_bag'),
                new ReferenceConfigurator('lotgd.core.tools'),
                new ReferenceConfigurator('lotgd.core.sanitize'),
            ])
            ->tag('form.type')
    ;
};
