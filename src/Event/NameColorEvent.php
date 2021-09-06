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

namespace Lotgd\Bundle\LodgeNameColorBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;

class NameColorEvent extends Event
{
    public const NAME_COLORIZE = 'lodge_name_color.colorize';

    private $fromName;
    private $newName;

    /**
     * Get the value of fromName
     */
    public function getFromName(): string
    {
        return $this->fromName;
    }

    /**
     * Set the value of fromName
     *
     * @return  self
     */
    public function setFromName(string $fromName): self
    {
        $this->fromName = $fromName;

        return $this;
    }

    /**
     * Get the value of newName
     */
    public function getNewName()
    {
        return $this->newName;
    }

    /**
     * Set the value of newName
     *
     * @return  self
     */
    public function setNewName($newName)
    {
        $this->newName = $newName;

        return $this;
    }
}
