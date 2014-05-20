<?php

namespace Regidium\CommonBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\EmbeddedDocument
 */
class AgentSession
{
    /* =============== Attributes =============== */

    /**
     * @MongoDB\Index
     * @MongoDB\Int
     */
    private $started_at;

    /**
     * @MongoDB\Index
     * @MongoDB\Int
     */
    private $ended_at;

    /**
     * @MongoDB\Index
     * @MongoDB\Int
     */
    private $last_visit;

    /**
     * @Assert\NotBlank
     * @MongoDB\Int
     */
    private $status;

    /**
     * @MongoDB\String
     */
    private $country;

    /**
     * @MongoDB\String
     */
    private $city;

    /**
     * @MongoDB\String
     */
    private $ip;

    /**
     * @MongoDB\String
     */
    private $device;

    /**
     * @MongoDB\String
     */
    private $os;

    /**
     * @MongoDB\String
     */
    private $browser;

    /**
     * @MongoDB\String
     */
    private $language;

    /* =============== Constants =============== */


    const STATUS_ONLINE   = 1;
    const STATUS_OFFLINE  = 3;

    static public function getStatuses()
    {
        return [
            self::STATUS_ONLINE,
            self::STATUS_OFFLINE
        ];
    }

    /* =============== General =============== */

    public function __construct()
    {
        $this->started_at = time();
        $this->last_visit = time();
        $this->status = self::STATUS_ONLINE;
    }

    public function toArray()
    {
        $return = [
            'started_at' => $this->started_at,
            'ended_at' => $this->ended_at,
            'last_name' => $this->last_name,
            'status' => $this->status,
            'country' => $this->country,
            'city' => $this->city,
            'ip' => $this->ip,
            'device' => $this->device,
            'os' => $this->os,
            'browser' => $this->browser,
            'language' => $this->language
        ];

        return $return;
    }

    /* =============== Get/Set=============== */


    /**
     * Set startedAt
     *
     * @param int $startedAt
     * @return self
     */
    public function setStartedAt($startedAt)
    {
        $this->started_at = $startedAt;
        return $this;
    }

    /**
     * Get startedAt
     *
     * @return int $startedAt
     */
    public function getStartedAt()
    {
        return $this->started_at;
    }

    /**
     * Set endedAt
     *
     * @param int $endedAt
     * @return self
     */
    public function setEndedAt($endedAt)
    {
        $this->ended_at = $endedAt;
        return $this;
    }

    /**
     * Get endedAt
     *
     * @return int $endedAt
     */
    public function getEndedAt()
    {
        return $this->ended_at;
    }

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

    /**
     * Set status
     *
     * @param int $status
     * @return self
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get status
     *
     * @return int $status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set lastVisit
     *
     * @param \DateTime $lastVisit
     * @return self
     */
    public function setLastVisit($lastVisit)
    {
        $this->last_visit = $lastVisit;
        return $this;
    }

    /**
     * Get lastVisit
     *
     * @return \DateTime $lastVisit
     */
    public function getLastVisit()
    {
        return $this->last_visit;
    }

}
