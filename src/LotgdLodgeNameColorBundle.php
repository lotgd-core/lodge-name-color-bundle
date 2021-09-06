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

namespace Lotgd\Bundle\LodgeNameColorBundle;

use Lotgd\Bundle\Contract\LotgdBundleInterface;
use Lotgd\Bundle\Contract\LotgdBundleTrait;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class LotgdLodgeNameColorBundle extends Bundle implements LotgdBundleInterface
{
    use LotgdBundleTrait;

    public const TRANSLATION_DOMAIN = 'bundle_lodge_name_color';

    /**
     * {@inheritDoc}
     */
    public function getLotgdName(): string
    {
        return 'Lodge Name Color';
    }

    /**
     * {@inheritDoc}
     */
    public function getLotgdVersion(): string
    {
        return '0.1.0';
    }

    /**
     * {@inheritDoc}
     */
    public function getLotgdIcon(): string
    {
        return 'paint brush';
    }

    /**
     * {@inheritDoc}
     */
    public function getLotgdDescription(): string
    {
        return 'Use donator points to colorize your name.';
    }

    /**
     * {@inheritDoc}
     */
    public function getLotgdDownload(): string
    {
        return 'https://github.com/lotgd-core/lodge-name-color-bundle';
    }
}
