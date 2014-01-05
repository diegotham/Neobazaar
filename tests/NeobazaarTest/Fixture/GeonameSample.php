<?php
namespace NeobazaarTest\Fixture;
 
use Doctrine\Common\DataFixtures\AbstractFixture,
    Doctrine\Common\Persistence\ObjectManager;
 
use Neobazaar\Entity\Geonames;

class GeonameSample 
    extends AbstractFixture
{
    protected $geoname;
    
    public function load(ObjectManager $manager)
    {
        $this->geoname = new Geonames();
        $this->geoname->setName('Geoname');
        $this->geoname->setAsciiname('Geoname ascii');
        $this->geoname->setAlternatenames('Geoname alternate');
        $this->geoname->setCountryCode('IT');
        $this->geoname->setLatitude('0.0');
        $this->geoname->setLongitude('0.0');
        $this->geoname->setFeatureClass('A');
        $this->geoname->setFeatureCode('PCLI');
        $this->geoname->setCc2('');
        $this->geoname->setPopulation(0);
        $this->geoname->setElevation(0);
        $this->geoname->setDem(0);
        $this->geoname->setTimezone('Europe/Rome');
        $this->geoname->setModificationDate(new \Datetime());
        $manager->persist($this->geoname);
        $manager->flush();
    }
    
    public function getGeoname()
    {
        return $this->geoname;
    }
}