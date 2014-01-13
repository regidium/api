<?php

namespace Regidium\FileBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

use Regidium\CommonBundle\Document\Interfaces\IdInterface;

/**
 * @MongoDB\Document(
 *      repositoryClass="Regidium\FileBundle\Repository\FileRepository",
 *      collection="files",
 *      requireIndexes=false
 *  )
 */
class File implements IdInterface
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
    private $name;

    /**
     * @MongoDB\File
     */
    private $file;

    /**
     * @MongoDB\String
     */
    private $uploadDate;

    /**
     * @MongoDB\String
     */
    private $length;

    /**
     * @MongoDB\String
     */
    private $chunkSize;

    /**
     * @MongoDB\String
     */
    private $md5;

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
     * Set file
     *
     * @param bin $file
     * @return self
     */
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * Get file
     *
     * @return bin $file
     */
    public function getFullname()
    {
        return $this->file;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }
}
