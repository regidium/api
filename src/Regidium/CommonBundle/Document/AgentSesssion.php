<?php

namespace Regidium\CommonBundle\Document;



/**
 * Regidium\CommonBundle\Document\AgentSesssion
 */
class AgentSesssion
{
    /**
     * @var string $country
     */
    protected $country;

    /**
     * @var string $city
     */
    protected $city;

    /**
     * @var string $ip
     */
    protected $ip;

    /**
     * @var string $device
     */
    protected $device;

    /**
     * @var string $os
     */
    protected $os;

    /**
     * @var string $browser
     */
    protected $browser;

    /**
     * @var string $language
     */
    protected $language;


    /**
     * Set country
     *
     * @param string $country
     * @return self
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * Get country
     *
     * @return string $country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return self
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * Get city
     *
     * @return string $city
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return self
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
        return $this;
    }

    /**
     * Get ip
     *
     * @return string $ip
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set device
     *
     * @param string $device
     * @return self
     */
    public function setDevice($device)
    {
        $this->device = $device;
        return $this;
    }

    /**
     * Get device
     *
     * @return string $device
     */
    public function getDevice()
    {
        return $this->device;
    }

    /**
     * Set os
     *
     * @param string $os
     * @return self
     */
    public function setOs($os)
    {
        $this->os = $os;
        return $this;
    }

    /**
     * Get os
     *
     * @return string $os
     */
    public function getOs()
    {
        return $this->os;
    }

    /**
     * Set browser
     *
     * @param string $browser
     * @return self
     */
    public function setBrowser($browser)
    {
        $this->browser = $browser;
        return $this;
    }

    /**
     * Get browser
     *
     * @return string $browser
     */
    public function getBrowser()
    {
        return $this->browser;
    }

    /**
     * Set language
     *
     * @param string $language
     * @return self
     */
    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }

    /**
     * Get language
     *
     * @return string $language
     */
    public function getLanguage()
    {
        return $this->language;
    }
}