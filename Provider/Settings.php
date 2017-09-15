<?php

namespace Creavo\OptionBundle\Provider;

use Creavo\OptionBundle\Entity\Setting;
use Creavo\OptionBundle\Interfaces\SettingInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Cache\Simple\AbstractCache;
use Symfony\Component\Cache\Simple\ArrayCache;
use Webmozart\Assert\Assert;
use Psr\SimpleCache\CacheInterface;

class Settings {

    /** @var AbstractCache */
    protected $cache;

    /** @var ObjectManager */
    protected $em;

    protected $settings=[];

    public function __construct(RegistryInterface $registry, CacheInterface $cache=null, $fetchAll=false) {
        $this->em=$registry->getManager();
        $this->setCache($cache!==null ? $cache : new ArrayCache());
        if($fetchAll) {
            $this->fetchAll();
        }
    }

    public function setCache(CacheInterface $cache) {
        $this->cache=$cache;
    }

    public function get($name) {

        if(isset($this->settings[$name])) {
            return $this->transformValueFromDatabase($this->settings[$name]['value'],$this->settings[$name]['type']);
        }

        /** @var Setting $setting */
        if($setting=$this->em->getRepository('CreavoOptionBundle:Setting')->findByName($name)) {
            return $this->transformValueFromDatabase($setting->getValue(),$setting->getType());
        }

        return null;
    }

    public function getUnCached($name) {

        /** @var Setting $setting */
        if($setting=$this->em->getRepository('CreavoOptionBundle:Setting')->findByName($name)) {
            return $this->transformValueFromDatabase($setting->getValue(),$setting->getType());
        }

        return null;
    }

    public function getFull($name) {

        if(isset($this->settings[$name])) {
            return $this->settings[$name];
        }

        /** @var Setting $setting */
        if($setting=$this->em->getRepository('CreavoOptionBundle:Setting')->findByName($name)) {
            $this->settings[$name]=$setting->toArray();
            return $setting->toArray();
        }

        return null;
    }

    public function getAll() {
        $data=[];

        foreach($this->settings AS $name=>$value) {
            $data[$name]=$this->get($name);
        }

        return $data;
    }

    public function getSection($section) {

    }

    public function set($name, $value, $type=null, $section=null) {

        if(!$type) {
            $type=SettingInterface::TYPE_STRING;
        }

        if(!$setting=$this->em->getRepository('CreavoOptionBundle:Setting')->findByName($name)) {
            $setting=new Setting();
            $setting->setName($name);
        }

        $setting->setValue($this->transformValueToDatabase($value,$type));
        $setting->setSection($section);
        $setting->setType($type);
        $setting->setUpdatedAt(new \DateTime('now'));
        $this->em->persist($setting);
        $this->em->flush();

        $this->settings[$name]=$setting->toArray();
    }

    public function fetchAll() {

    }

    public function fetch($name) {

    }

    protected function transformValueToDatabase($value,$type) {

        if($type==SettingInterface::TYPE_BOOLEAN) {
            Assert::boolean($value);
            return $value ? 1 : 0;
        }

        if($type==SettingInterface::TYPE_DATE_TIME) {
            if(!$value instanceof \DateTimeInterface) {
                throw new \Exception('value is not an datetime-object');
            }
            return $value->format('Y-m-d H:i:s');
        }

        if($type==SettingInterface::TYPE_ARRAY) {
            Assert::isArray($value);
            return json_encode($value);
        }

        return $value;
    }

    protected function transformValueFromDatabase($value,$type) {

        if($type==SettingInterface::TYPE_STRING) {
            return (string)$value;
        }

        if($type==SettingInterface::TYPE_INTEGER) {
            return (integer)$value;
        }

        if($type==SettingInterface::TYPE_BOOLEAN) {
            if(in_array($value,[true,1,'y','yes','true'])) {
                return true;
            }elseif(in_array($value,[false,0,'n','no','false'])) {
                return false;
            }
        }

        if($type==SettingInterface::TYPE_DATE_TIME) {
            $dt=\DateTime::createFromFormat('Y-m-d H:i:s',$value);
            return $dt;
        }

        if($type==SettingInterface::TYPE_ARRAY) {
            return json_decode($value,true);
        }

        return $value;
    }
}
