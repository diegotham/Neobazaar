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
     * @var string
     */
    private $hashLength;
        
    /**
     * Get HashLength
     *
     * @return 
     */
    public function getHashLength() 
    {
        return $this->hashLength;
    }
    
    /**
     * Set HashLength
     *
     * @return 
     */
    public function setHashLength($hashLength) 
    {
        $this->hashLength = $hashLength;
        
        return $this;
    }
        
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