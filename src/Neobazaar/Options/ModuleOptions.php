<?php
namespace Neobazaar\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions 
    extends AbstractOptions 
        implements
            HashSaltOptionsInterface
{
    /**
     * @var string
     */
    protected $hashSalt;
        
    /**
     * Get Salt
     *
     * @return string
     */
    public function getHashSalt() 
    {
        return $this->hashSalt;
    }
    
    /**
     * Set Salt
     *
     * @return ModuleOptions
     */
    public function setHashSalt($hashSalt) 
    {
        $this->hashSalt = $hashSalt;
        
        return $this;
    }
}