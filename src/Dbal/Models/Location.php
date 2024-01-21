<?php

namespace Qween\Location\Dbal\Models;

use Qween\Location\Dbal\Entity;

/**
 * @ORM\Entity
 * @ORM\Table(name="locations")
 */
class Location extends Entity
{
    /**
     * @ORM\Id
     * @ORM\Column(name="ip", type="string")
     */
    private $ip;

    /**
     * @ORM\Column(name="hostname", type="string")
     */
    private $hostname;

    /**
     * @ORM\Column(name="continent_code", type="string")
     */
    private $continentCode;

    /**
     * @ORM\Column(name="continent_name", type="string")
     */
    private $continentName;

    /**
     * @ORM\Column(name="country_code2", type="string")
     */
    private $countryCode2;

    /**
     * @ORM\Column(name="country_code3", type="string")
     */
    private $countryCode3;

    /**
     * @ORM\Column(name="country_name", type="string")
     */
    private $countryName;

    /**
     * @ORM\Column(name="country_capital", type="string")
     */
    private $countryCapital;

    /**
     * @ORM\Column(name="state_prov", type="string")
     */
    private $stateProv;

    /**
     * @ORM\Column(name="district", type="string")
     */
    private $district;

    /**
     * @ORM\Column(name="city", type="string")
     */
    private $city;

    /**
     * @ORM\Column(name="zipcode", type="string")
     */
    private $zipcode;

    /**
     * @ORM\Column(name="latitude", type="float")
     */
    private $latitude;

    /**
     * @ORM\Column(name="longitude", type="float")
     */
    private $longitude;

    /**
     * @ORM\Column(name="is_eu", type="boolean")
     */
    private $isEu;

    /**
     * @ORM\Column(name="calling_code", type="string")
     */
    private $callingCode;

    /**
     * @ORM\Column(name="country_tld", type="string")
     */
    private $countryTld;

    /**
     * @ORM\Column(name="languages", type="string")
     */
    private $languages;

    /**
     * @ORM\Column(name="country_flag", type="string")
     */
    private $countryFlag;

    /**
     * @ORM\Column(name="geoname_id", type="string")
     */
    private $geonameId;

    /**
     * @ORM\Column(name="isp", type="string")
     */
    private $isp;

    /**
     * @ORM\Column(name="connection_type", type="string")
     */
    private $connectionType;

    /**
     * @ORM\Column(name="organization", type="string")
     */
    private $organization;

    /**
     * @ORM\Column(name="asn", type="string")
     */
    private $asn;

    /**
     * @ORM\Column(name="currency_code", type="string")
     */
    private $currencyCode;

    /**
     * @ORM\Column(name="currency_name", type="string")
     */
    private $currencyName;

    /**
     * @ORM\Column(name="currency_symbol", type="string")
     */
    private $currencySymbol;

    /**
     * @ORM\Column(name="time_zone_name", type="string")
     */
    private $timeZoneName;

    /**
     * @ORM\Column(name="time_zone_offset", type="string")
     */
    private $timeZoneOffset;

    /**
     * @ORM\Column(name="time_zone_current_time", type="string")
     */
    private $timeZoneCurrentTime;

    /**
     * @ORM\Column(name="time_zone_current_time_unix", type="string")
     */
    private $timeZoneCurrentTimeUnix;

    /**
     * @ORM\Column(name="time_zone_is_dst", type="boolean")
     */
    private $timeZoneIsDst;

    /**
     * @ORM\Column(name="time_zone_dst_savings", type="integer")
     */
    private $timeZoneDstSavings;

    function setId($id)
    {
        $this->ip = $id;
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->countryName;
    }

    public function setCountry(string $countryName)
    {
        $this->countryName = $countryName;
    }

    public function setRegion(?string $region)
    {
        $this->stateProv = $region;
    }

    public function setCity(?string $city)
    {
        $this->city = $city;
    }

    public function getRegion()
    {
        return $this->stateProv;
    }

    public function getCity()
    {
        return $this->city;
    }
}