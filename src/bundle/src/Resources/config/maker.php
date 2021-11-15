<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Hasura\Bundle\Maker\MakeEntity;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services();
    $services
        ->set(
            'hasura.maker.maker_entity',
            MakeEntity::class
        )
            ->decorate('maker.maker.make_entity', priority: -8)
            ->args(
                [
                    service('.inner'),
                    service('maker.generator')
                ]
            )
    ;
};