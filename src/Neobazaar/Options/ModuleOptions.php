<?php
namespace Neobazaar\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions 
    extends AbstractOptions 
        implements
            SaltOptionsInterface
{
    /**
     * @var string
     */
    protected $salt;
        
    /**
     * Get Salt
     *
     * @return string
     */
    public function getSalt() 
    {
        return $this->salt;
    }
    
    /**
     * Set Salt
     *
     * @return ModuleOptions
     */
    public function setSalt($salt) 
    {
        $this->salt = $salt;
        
        return $this;
    }
}