<?php
/**
 * @author Russell Kvashnin <russell.kvashnin@gmail.com>
 */

namespace Regidium\CommonBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @MongoDB\Document(
 *      repositoryClass="Regidium\CommonBundle\Repository\ResetPasswordRepository",
 *      collection="reset_password",
 *      requireIndexes=false
 *  )
 */
class ResetPasswordRequest
{

    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @MongoDB\String
     * @MongoDB\UniqueIndex(safe="true")
     */
    private $secretCode;

    /**
     * @MongoDB\Index
     * @MongoDB\ReferenceOne(targetDocument="Regidium\CommonBundle\Document\Agent")
     */
    private $agent;


    public function __construct(Agent $agent)
    {
        $this->secretCode = uniqid();
        $this->agent = $agent;
    }
    /**
     * @return mixed
     */
    public function getAgent()
    {
        return $this->agent;
    }

    /**
     * @param mixed $agent
     */
    public function setAgent($agent)
    {
        $this->agent = $agent;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getSecretCode()
    {
        return $this->secretCode;
    }

    /**
     * @param mixed $secretCode
     */
    public function setSecretCode($secretCode)
    {
        $this->secretCode = $secretCode;
    }

    public function toArray()
    {
        return [
            'secretCode' => $this->secretCode
        ];
    }

} 