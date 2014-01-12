<?php

namespace Regidium\UserBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

use Regidium\CommonBundle\Document\Interfaces\IdableInterface;
use Regidium\CommonBundle\Document\Interfaces\StatebleInteface;

use Regidium\AuthBundle\Document\Auth;

/**
 * @MongoDB\Document(
 *      repositoryClass="Regidium\UserBundle\Repository\UserRepository",
 *      collection="users",
 *      requireIndexes=false
 *  )
 *
 * @todo Country, City перевести в модели
 * @todo Динамическую информацию перевести в Session
 */
class User implements IdableInterface, StatebleInteface
{
    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @MongoDB\String @MongoDB\UniqueIndex(safe="true")
     */
    private $uid;

    /**
     * @MongoDB\String
     */
    private $fullname;

    /**
     * @MongoDB\String @MongoDB\UniqueIndex(safe="true")
     */
    private $email;

    /**
     * @MongoDB\String
     */
    private $password;

    /**
     * @Assert\NotBlank
     * @MongoDB\Int
     */
    private $state;

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
     * @MongoDB\Int
     */
    private $amount_returned;

    /**
     * @MongoDB\Int
     */
    private $amount_chats;

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
    private $keyword;

    /**
     * @MongoDB\Hash
     */
    private $external_service;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Regidium\AuthBundle\Document\Auth", mappedBy="user")
    */
    protected $auths;

    const STATE_DEFAULT = 1;
    const STATE_BLOCKED = 2;
    const STATE_DELETED = 3;

    static public function getStates()
    {
        return array(
                self::STATE_DEFAULT,
                self::STATE_BLOCKED,
                self::STATE_DELETED
            );
    }

    public function __construct()
    {
        $this->setUid(uniqid());
        $this->setState(self::STATE_DEFAULT);
        $this->setExternalService([]);
    }

    public function __toString()
    {
        return $this->fullname;
    }

    /**
     * Set id
     *
     * @param int $id
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
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set uid
     *
     * @param int $uid
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
     * @return uid $uid
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * Set fullname
     *
     * @param string $fullname
     * @return self
     */
    public function setFullname($fullname)
    {
        $this->fullname = $fullname;
        return $this;
    }

    /**
     * Get fullname
     *
     * @return string $fullname
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = strtolower($email);
        return $this;
    }

    /**
     * Get email
     *
     * @return string $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return self
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Get password
     *
     * @return string $password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set state
     *
     * @param string $state
     *
     * @throws \InvalidArgumentException
     * @return self
     */
    public function setState($state)
    {
        if (!in_array($state, self::getStates())) {
            throw new \InvalidArgumentException("Invalid state: {$state}");
        }
        $this->state = $state;
        return $this;
    }

    /**
     * Get state
     *
     * @return int $state
     */
    public function getState()
    {
        return $this->state;
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
     * Set amountReturned
     *
     * @param int $amountReturned
     * @return self
     */
    public function setAmountReturned($amountReturned)
    {
        $this->amount_returned = $amountReturned;
        return $this;
    }

    /**
     * Get amountReturned
     *
     * @return int $amountReturned
     */
    public function getAmountReturned()
    {
        return $this->amount_returned;
    }

    /**
     * Set amountChats
     *
     * @param int $amountChats
     * @return self
     */
    public function setAmountChats($amountChats)
    {
        $this->amount_chats = $amountChats;
        return $this;
    }

    /**
     * Get amountChats
     *
     * @return int $amountChats
     */
    public function getAmountChats()
    {
        return $this->amount_chats;
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
     * Set keyword
     *
     * @param string $keyword
     * @return self
     */
    public function setKeyword($keyword)
    {
        $this->keyword = $keyword;
        return $this;
    }

    /**
     * Get keyword
     *
     * @return string $keyword
     */
    public function getKeyword()
    {
        return $this->keyword;
    }

    /**
     * Set externalService
     *
     * @param hash $externalService
     * @return self
     */
    public function setExternalService($externalService)
    {
        $this->external_service = $externalService;
        return $this;
    }

    /**
     * Get externalService
     *
     * @return hash $externalService
     */
    public function getExternalService()
    {
        return $this->external_service;
    }

    /**
     * Add auth
     *
     * @param Auth $auth
     */
    public function addAuth(Auth $auth)
    {
        $this->auths[] = $auth;
    }

    /**
     * Remove auth
     *
     * @param Auth $auth
     */
    public function removeAuth(Auth $auth)
    {
        $this->auths->removeElement($auth);
    }

    /**
     * Get auths
     *
     * @return \Doctrine\Common\Collections\Collection $auths
     */
    public function getAuths()
    {
        return $this->auths;
    }
}
