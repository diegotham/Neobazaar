<?php 
namespace Neobazaar\Entity;

use Doctrine\ORM\Mapping as ORM;

use Zend\InputFilter\Factory as InputFactory,
    Zend\InputFilter\InputFilter,
    Zend\InputFilter\InputFilterAwareInterface,
    Zend\InputFilter\InputFilterInterface,
    Zend\Debug\Debug;

use Datetime;

/** 
 * @ORM\MappedSuperclass
 */
class MappedSuperclassBase
    implements InputFilterAwareInterface
{
    /**
     * @var InputFilterInterface
     */
    private $inputFilter;
    
    /**
     * Exchange array - used in ZF2 form
     *
     * @param array $data An array of data
     */
    public function exchangeArray($data)
    {
        foreach($data as $prop => $value) {
            $methodName = 'set' . ucfirst($prop);
            if(method_exists ($this, $methodName)) {
                call_user_func(array($this, $methodName), $value);
            }
        }
    }
    
    /**
     * Restituisce le proprietà dell'oggetto allo scopo di popolare i form
     * NOTA BENE: questo metodo restituisce i valori 
     * per le proprieta con access modifier protected.
     * 
     * Le proprietà private non saranno esposte, QUINDI 
     * se non si desidera che siano restituiti i valori di certe proprietà 
     * ( es. password ) basterà dichiararle come private.
     * 
     *  Se questo metodo non restituisce nulla per una entità probabilmente 
     *  le proprietà sono tutte private (il generatore le genera private)
     * 
     * @return multitype:
     */
    public function getArrayCopy()
    {
        $vars = get_object_vars($this);
        foreach($vars as $k => $var) {
            if($var instanceof Datetime) {
                $vars[$k . '_f'] = $var->format(Datetime::ISO8601);
            }
        }
        return $vars;
    }
    
    /**
     * Set input method
     *
     * @param InputFilterInterface $inputFilter
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        $this->inputFilter = $inputFilter;
    }
    
    /**
     * Get input filter
     *
     * @return InputFilterInterface
     */
    public function getInputFilter()
    {
        return $this->inputFilter;
    }  
}