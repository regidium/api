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

    public function toArray()
    {
        $return = [
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

}
