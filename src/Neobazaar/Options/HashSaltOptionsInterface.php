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
    public function setHashSalt($hashSalt);

    /**
     * Get $hashsalt
     *
     * @return string
     */
    public function getHashSalt();
    
    /**
     * Set hash length
     *
     * @param string $hashLength
     * @return ModuleOptions
     */
    public function setHashLength($hashLength);

    /**
     * Get $hashLength
     *
     * @return string
     */
    public function getHashLength();
}