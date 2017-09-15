<?php

namespace Creavo\OptionBundle\Entity;

use Creavo\OptionBundle\Interfaces\SettingInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Setting
 *
 * @ORM\Table(name="crv_ob_settings", indexes={
 *      @ORM\Index(name="name_idx", columns={"name"})
 * })
 * @ORM\Entity(repositoryClass="Creavo\OptionBundle\Repository\SettingRepository")
 */
class Setting implements SettingInterface
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="text", nullable=true)
     */
    private $value;

    /**
     * @var string
     *
     * @ORM\Column(name="section", type="string", length=255, nullable=true)
     */
    private $section;

    /**
     * @var int
     *
     * @ORM\Column(name="type", type="smallint")
     */
    private $type=self::TYPE_STRING;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;


    public function __construct() {
        $this->updatedAt=new \DateTime('now');
    }

    public static function getTypeName($type) {
        if($type==self::TYPE_STRING) {
            return 'string';
        }
        if($type==self::TYPE_INTEGER) {
            return 'integer';
        }
        if($type==self::TYPE_BOOLEAN) {
            return 'boolean';
        }
        if($type==self::TYPE_DATE_TIME) {
            return 'dateTime';
        }
        if($type==self::TYPE_ARRAY) {
            return 'array';
        }

        return 'unknown';
    }

    public function getId(){
        return $this->id;
    }

    public function toArray() {
        return [
            'name'=>$this->getName(),
            'section'=>$this->getSection(),
            'value'=>$this->getValue(),
            'type'=>$this->getType(),
            'updatedAt'=>$this->getUpdatedAt(),
        ];
    }

    public function setName($name){
        $this->name = $name;
        return $this;
    }

    public function getName(){
        return $this->name;
    }

    public function setValue($value){
        $this->value = $value;
        return $this;
    }

    public function getValue(){
        return $this->value;
    }

    public function setSection($section){
        $this->section = $section;
        return $this;
    }

    public function getSection(){
        return $this->section;
    }

    public function setType($type){
        $this->type = $type;
        return $this;
    }

    public function getType(){
        return $this->type;
    }

    public function setUpdatedAt($updatedAt){
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getUpdatedAt(){
        return $this->updatedAt;
    }
}

