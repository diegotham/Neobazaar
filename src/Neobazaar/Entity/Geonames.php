<?php

namespace Neobazaar\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Geonames
 *
 * @ORM\Table(name="geonames")
 * @ORM\Entity(repositoryClass="Neobazaar\Entity\Repository\GeonamesRepository")
 */
class Geonames
{
    /**
     * @var integer
     *
     * @ORM\Column(name="geoname_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $geonameId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=200, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="asciiname", type="string", length=200, nullable=false)
     */
    private $asciiname;

    /**
     * @var string
     *
     * @ORM\Column(name="alternatenames", type="string", length=6000, nullable=false)
     */
    private $alternatenames;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=49, nullable=false)
     */
    private $url;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float", nullable=false)
     */
    private $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float", nullable=false)
     */
    private $longitude;

    /**
     * @var string
     *
     * @ORM\Column(name="feature_class", type="string", length=1, nullable=false)
     */
    private $featureClass;

    /**
     * @var string
     *
     * @ORM\Column(name="feature_code", type="string", length=10, nullable=false)
     */
    private $featureCode;

    /**
     * @var string
     *
     * @ORM\Column(name="country_code", type="string", length=2, nullable=false)
     */
    private $countryCode;

    /**
     * @var string
     *
     * @ORM\Column(name="cc2", type="string", length=60, nullable=false)
     */
    private $cc2;

    /**
     * @var string
     *
     * @ORM\Column(name="admin1_code", type="string", length=20, nullable=false)
     */
    private $admin1Code;

    /**
     * @var string
     *
     * @ORM\Column(name="admin2_code", type="string", length=80, nullable=false)
     */
    private $admin2Code;

    /**
     * @var string
     *
     * @ORM\Column(name="admin3_code", type="string", length=20, nullable=false)
     */
    private $admin3Code;

    /**
     * @var string
     *
     * @ORM\Column(name="admin4_code", type="string", length=20, nullable=false)
     */
    private $admin4Code;

    /**
     * @var integer
     *
     * @ORM\Column(name="population", type="integer", nullable=false)
     */
    private $population;

    /**
     * @var integer
     *
     * @ORM\Column(name="elevation", type="integer", nullable=false)
     */
    private $elevation;

    /**
     * @var integer
     *
     * @ORM\Column(name="dem", type="integer", nullable=false)
     */
    private $dem;

    /**
     * @var string
     *
     * @ORM\Column(name="timezone", type="string", length=30, nullable=false)
     */
    private $timezone;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modification_date", type="date", nullable=false)
     */
    private $modificationDate;



    /**
     * Get geonameId
     *
     * @return integer 
     */
    public function getGeonameId()
    {
        return $this->geonameId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Geonames
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set asciiname
     *
     * @param string $asciiname
     * @return Geonames
     */
    public function setAsciiname($asciiname)
    {
        $this->asciiname = $asciiname;
    
        return $this;
    }

    /**
     * Get asciiname
     *
     * @return string 
     */
    public function getAsciiname()
    {
        return $this->asciiname;
    }

    /**
     * Set alternatenames
     *
     * @param string $alternatenames
     * @return Geonames
     */
    public function setAlternatenames($alternatenames)
    {
        $this->alternatenames = $alternatenames;
    
        return $this;
    }

    /**
     * Get alternatenames
     *
     * @return string 
     */
    public function getAlternatenames()
    {
        return $this->alternatenames;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Geonames
     */
    public function setUrl($url)
    {
        $this->url = $url;
    
        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     * @return Geonames
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    
        return $this;
    }

    /**
     * Get latitude
     *
     * @return float 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     * @return Geonames
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    
        return $this;
    }

    /**
     * Get longitude
     *
     * @return float 
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set featureClass
     *
     * @param string $featureClass
     * @return Geonames
     */
    public function setFeatureClass($featureClass)
    {
        $this->featureClass = $featureClass;
    
        return $this;
    }

    /**
     * Get featureClass
     *
     * @return string 
     */
    public function getFeatureClass()
    {
        return $this->featureClass;
    }

    /**
     * Set featureCode
     *
     * @param string $featureCode
     * @return Geonames
     */
    public function setFeatureCode($featureCode)
    {
        $this->featureCode = $featureCode;
    
        return $this;
    }

    /**
     * Get featureCode
     *
     * @return string 
     */
    public function getFeatureCode()
    {
        return $this->featureCode;
    }

    /**
     * Set countryCode
     *
     * @param string $countryCode
     * @return Geonames
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
    
        return $this;
    }

    /**
     * Get countryCode
     *
     * @return string 
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * Set cc2
     *
     * @param string $cc2
     * @return Geonames
     */
    public function setCc2($cc2)
    {
        $this->cc2 = $cc2;
    
        return $this;
    }

    /**
     * Get cc2
     *
     * @return string 
     */
    public function getCc2()
    {
        return $this->cc2;
    }

    /**
     * Set admin1Code
     *
     * @param string $admin1Code
     * @return Geonames
     */
    public function setAdmin1Code($admin1Code)
    {
        $this->admin1Code = $admin1Code;
    
        return $this;
    }

    /**
     * Get admin1Code
     *
     * @return string 
     */
    public function getAdmin1Code()
    {
        return $this->admin1Code;
    }

    /**
     * Set admin2Code
     *
     * @param string $admin2Code
     * @return Geonames
     */
    public function setAdmin2Code($admin2Code)
    {
        $this->admin2Code = $admin2Code;
    
        return $this;
    }

    /**
     * Get admin2Code
     *
     * @return string 
     */
    public function getAdmin2Code()
    {
        return $this->admin2Code;
    }

    /**
     * Set admin3Code
     *
     * @param string $admin3Code
     * @return Geonames
     */
    public function setAdmin3Code($admin3Code)
    {
        $this->admin3Code = $admin3Code;
    
        return $this;
    }

    /**
     * Get admin3Code
     *
     * @return string 
     */
    public function getAdmin3Code()
    {
        return $this->admin3Code;
    }

    /**
     * Set admin4Code
     *
     * @param string $admin4Code
     * @return Geonames
     */
    public function setAdmin4Code($admin4Code)
    {
        $this->admin4Code = $admin4Code;
    
        return $this;
    }

    /**
     * Get admin4Code
     *
     * @return string 
     */
    public function getAdmin4Code()
    {
        return $this->admin4Code;
    }

    /**
     * Set population
     *
     * @param integer $population
     * @return Geonames
     */
    public function setPopulation($population)
    {
        $this->population = $population;
    
        return $this;
    }

    /**
     * Get population
     *
     * @return integer 
     */
    public function getPopulation()
    {
        return $this->population;
    }

    /**
     * Set elevation
     *
     * @param integer $elevation
     * @return Geonames
     */
    public function setElevation($elevation)
    {
        $this->elevation = $elevation;
    
        return $this;
    }

    /**
     * Get elevation
     *
     * @return integer 
     */
    public function getElevation()
    {
        return $this->elevation;
    }

    /**
     * Set dem
     *
     * @param integer $dem
     * @return Geonames
     */
    public function setDem($dem)
    {
        $this->dem = $dem;
    
        return $this;
    }

    /**
     * Get dem
     *
     * @return integer 
     */
    public function getDem()
    {
        return $this->dem;
    }

    /**
     * Set timezone
     *
     * @param string $timezone
     * @return Geonames
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;
    
        return $this;
    }

    /**
     * Get timezone
     *
     * @return string 
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * Set modificationDate
     *
     * @param \DateTime $modificationDate
     * @return Geonames
     */
    public function setModificationDate($modificationDate)
    {
        $this->modificationDate = $modificationDate;
    
        return $this;
    }

    /**
     * Get modificationDate
     *
     * @return \DateTime 
     */
    public function getModificationDate()
    {
        return $this->modificationDate;
    }
}