<?php
namespace Neobazaar\Options;

interface HashSaltOptionsInterface
{
    /**
     * Set salt
     *
     * @param string $hashsalt
     * @return ModuleOptions
     */
    public function setHashSalt($hashsalt);

    /**
     * Get $hashsalt
     *
     * @return string
     */
    public function getHashSalt();
}