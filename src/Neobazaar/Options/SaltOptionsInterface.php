<?php
namespace Neobazaar\Options;

interface SaltOptionsInterface
{
    /**
     * Set salt
     *
     * @param string $salt
     * @return ModuleOptions
     */
    public function setSalt($salt);

    /**
     * Get sender
     *
     * @return string
     */
    public function getSalt();
}