<?php

namespace Regidium\CommonBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @MongoDB\Document(
 *      repositoryClass="Regidium\CommonBundle\Repository\SessionRepository",
 *      collection="sessions",
 *      requireIndexes=false
 *  )
 */
class Session
{
    /* =============== Attributes =============== */

    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @MongoDB\String
     * @MongoDB\UniqueIndex(safe="true")
     */
    private $uid;

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

    /* =============== References =============== */

    /**
     * @MongoDB\Index
     * @MongoDB\ReferenceOne(targetDocument="Regidium\CommonBundle\Document\Agent", cascade={"persist", "merge", "detach"}, inversedBy="session")
     */
    private $agent;

    /* =============== Constants =============== */


    const STATUS_ONLINE   = 1;
    const STATUS_CHATTING = 2;
    const STATUS_OFFLINE  = 3;

    static public function getStatuses()
    {
        return [
            self::STATUS_ONLINE,
            self::STATUS_CHATTING,
            self::STATUS_OFFLINE
        ];
    }

    /* =============== General =============== */

    public function __construct()
    {
        $this->setUid(uniqid());
        $this->setLastVisit(time());
        $this->setStartedAt(time());
        $this->setStatus(self::STATUS_ONLINE);
    }

    public function toArray()
    {
        $return = [
            'uid' => $this->getUid(),
            'started_at' => $this->getStartedAt(),
            'ended_at' => $this->getEndedAt(),
            'last_visit' => $this->getLastVisit(),
            'status' => $this->getStatus(),
            'country' => $this->getCountry(),
            'city' => $this->getCity(),
            'ip' => $this->getIp(),
            'device' => $this->getDevice(),
            'os' => $this->getOs(),
            'browser' => $this->getBrowser(),
            'language' => $this->getLanguage()
        ];

        return $return;
    }

    /* =============== Get/Set=============== */

    /**
     * Set id
     *
     * @param string $id
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get id
     *
     * @return string $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set uid
     *
     * @param string $uid
     * @return self
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
        return $this;
    }

    /**
     * Get uid
     *
     * @return string $uid
     */
    public function getUid()
    {
        return $this->uid;
    }

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
     * Set lastVisit
     *
     * @param int $lastVisit
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
     * @return int $lastVisit
     */
    public function getLastVisit()
    {
        return $this->last_visit;
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
     * Set agent
     *
     * @param Regidium\CommonBundle\Document\Agent $agent
     * @return self
     */
    public function setAgent(\Regidium\CommonBundle\Document\Agent $agent)
    {
        $this->agent = $agent;
        return $this;
    }

    /**
     * Get agent
     *
     * @return Regidium\CommonBundle\Document\Agent $agent
     */
    public function getAgent()
    {
        return $this->agent;
    }
}
