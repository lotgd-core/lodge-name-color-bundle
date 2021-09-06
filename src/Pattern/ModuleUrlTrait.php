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

namespace Lotgd\Bundle\LodgeNameColorBundle\Pattern;

use Lotgd\Bundle\LodgeNameColorBundle\Controller\LodgeNameColorController;

trait ModuleUrlTrait
{
    public function getModuleUrl(string $method, string $query = '')
    {
        return "runmodule.php?method={$method}&controller=".urlencode(LodgeNameColorController::class).$query;
    }
}
