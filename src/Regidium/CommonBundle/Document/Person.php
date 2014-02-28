<?php

namespace Regidium\CommonBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @MongoDB\Document(
 *      repositoryClass="Regidium\CommonBundle\Repository\PersonRepository",
 *      collection="persons",
 *      requireIndexes=false
 *  )
 *
 * @todo Country, City перевести в модели
 */
class Person
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
     * @MongoDB\String
     */
    private $model_type;

    /**
     * @MongoDB\String
     */
    private $fullname;

    /**
     * @MongoDB\String
     */
    private $avatar;

    /**
     * @MongoDB\String
     * @MongoDB\UniqueIndex(safe="true")
     */
    private $email;

    /**
     * @MongoDB\String
     */
    private $password;

    /**
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
    private $os;

    /**
     * @MongoDB\String
     */
    private $device;

    /**
     * @MongoDB\String
     */
    private $browser;

    /**
     * @MongoDB\String
     */
    private $keyword;

    /**
     * @MongoDB\String
     */
    private $language;

    /**
     * @MongoDB\Hash
     */
    private $external_service;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Regidium\CommonBundle\Document\Agent", cascade={"all"}, inversedBy="person")
    */
    private $agent;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Regidium\CommonBundle\Document\User", cascade={"all"}, inversedBy="person")
     */
    private $user;

    /* =============== References =============== */

    /**
     * @MongoDB\ReferenceMany(targetDocument="Regidium\CommonBundle\Document\Chat", mappedBy="sender")
     */
    private $output_messages;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Regidium\CommonBundle\Document\Chat", mappedBy="receiver")
     */
    private $input_messages;

    /* =============== Constants =============== */

    const STATUS_DEFAULT = 1;
    const STATUS_BLOCKED = 2;
    const STATUS_DELETED = 3;

    static public function getStatuses()
    {
        return [
            self::STATUS_DEFAULT,
            self::STATUS_BLOCKED,
            self::STATUS_DELETED
        ];
    }

    /* =============== General =============== */

    public function __construct()
    {
        $this->setUid(uniqid());
        $this->setStatus(self::STATUS_DEFAULT);
        $this->setExternalService([]);

        $this->chats = new ArrayCollection();
        $this->output_messages = new ArrayCollection();
        $this->input_messages = new ArrayCollection();

        $this->setModelType('person');
    }

    public function __toString()
    {
        return $this->fullname;
    }

    public function toArray()
    {
        return [
           'uid' => $this->uid,
           'fullname' => $this->fullname,
           'avatar' => $this->avatar,
           'email' => $this->email,
           'country' => $this->country,
           'city' => $this->city,
           'ip' => $this->ip,
           'os' => $this->os,
           'device' => $this->device,
           'browser' => $this->browser,
           'keyword' => $this->keyword,
           'language' => $this->language,
           'status' => $this->status
        ];
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
     * Set modelType
     *
     * @param string $modelType
     * @return self
     */
    public function setModelType($modelType)
    {
        $this->model_type = $modelType;
        return $this;
    }

    /**
     * Get modelType
     *
     * @return string $modelType
     */
    public function getModelType()
    {
        return $this->model_type;
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
     * Set avatar
     *
     * @param string $avatar
     * @return self
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
        return $this;
    }

    /**
     * Get avatar
     *
     * @return string $avatar
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;
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
     * @return \Regidium\CommonBundle\Document\Agent $agent
     */
    public function getAgent()
    {
        return $this->agent;
    }

    /**
     * Set user
     *
     * @param Regidium\CommonBundle\Document\User $user
     * @return self
     */
    public function setUser(\Regidium\CommonBundle\Document\User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return \Regidium\CommonBundle\Document\User $user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add outputMessage
     *
     * @param \Regidium\CommonBundle\Document\Chat $outputMessage
     */
    public function addOutputMessage(\Regidium\CommonBundle\Document\Chat $outputMessage)
    {
        $this->output_messages[] = $outputMessage;
    }

    /**
     * Remove outputMessage
     *
     * @param Regidium\CommonBundle\Document\Chat $outputMessage
     */
    public function removeOutputMessage(\Regidium\CommonBundle\Document\Chat $outputMessage)
    {
        $this->output_messages->removeElement($outputMessage);
    }

    /**
     * Get outputMessages
     *
     * @return Doctrine\Common\Collections\Collection $outputMessages
     */
    public function getOutputMessages()
    {
        return $this->output_messages;
    }

    /**
     * Add inputMessage
     *
     * @param Regidium\CommonBundle\Document\Chat $inputMessage
     */
    public function addInputMessage(\Regidium\CommonBundle\Document\Chat $inputMessage)
    {
        $this->input_messages[] = $inputMessage;
    }

    /**
     * Remove inputMessage
     *
     * @param Regidium\CommonBundle\Document\Chat $inputMessage
     */
    public function removeInputMessage(\Regidium\CommonBundle\Document\Chat $inputMessage)
    {
        $this->input_messages->removeElement($inputMessage);
    }

    /**
     * Get inputMessages
     *
     * @return Doctrine\Common\Collections\Collection $inputMessages
     */
    public function getInputMessages()
    {
        return $this->input_messages;
    }
}
