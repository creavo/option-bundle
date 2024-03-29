<?php

namespace Creavo\OptionBundle\Provider;

use Creavo\OptionBundle\Entity\Setting;
use Creavo\OptionBundle\Interfaces\SettingInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Simple\AbstractCache;
use Webmozart\Assert\Assert;
use Psr\SimpleCache\CacheInterface;

class Settings {

    /** @var AbstractCache|null */
    protected $cache;

    /** @var EntityManagerInterface */
    protected $em;

    /** @var array */
    protected $settings=[];

    /**
     * Settings constructor.
     * @param RegistryInterface $registry
     * @param CacheInterface|null $cache
     * @param bool $fetchAll
     */
    public function __construct(EntityManagerInterface $em, CacheInterface $cache=null, $fetchAll=false) {
        $this->em=$em;
        if($cache) {
            $this->setCache($cache);
        }
        if($fetchAll) {
            $this->fetchAll();
        }
    }

    /**
     * @param CacheInterface $cache
     */
    public function setCache(CacheInterface $cache) {
        $this->cache=$cache;
    }

    /**
     * returns option with caching
     *
     * @param $name
     * @param null $default
     * @return bool|\DateTime|int|mixed|null|string
     */
    public function get($name, $default=null) {

        if(isset($this->settings[$name])) {
            return $this->transformValueFromDatabase($this->settings[$name]['value'],$this->settings[$name]['type']);
        }

        /** @var Setting $setting */
        if($setting=$this->em->getRepository(Setting::class)->findByName($name)) {
            $this->addToCache($setting);
            return $this->transformValueFromDatabase($setting->getValue(),$setting->getType());
        }

        return $default;
    }

    /**
     * checks if option is existing
     *
     * @param $name
     * @return bool
     */
    public function has($name) {

        if(isset($this->settings[$name])) {
            return true;
        }

        /** @var Setting $setting */
        if($setting=$this->em->getRepository(Setting::class)->findByName($name)) {
            $this->addToCache($setting);
            return true;
        }

        return false;
    }

    /**
     * returns option without any caching (directly from database)
     *
     * @param $name
     * @param null $default
     * @return bool|\DateTime|mixed|null
     */
    public function getUnCached($name, $default=null) {

        /** @var Setting $setting */
        if($setting=$this->em->getRepository(Setting::class)->findByName($name)) {
            $this->addToCache($setting);
            return $this->transformValueFromDatabase($setting->getValue(),$setting->getType());
        }

        return $default;
    }

    /**
     * returns array with additional data to the option like type, section and last updated
     *
     * @param $name
     * @return array|mixed|null
     */
    public function getFull($name) {

        if(isset($this->settings[$name])) {
            return $this->settings[$name];
        }

        /** @var Setting $setting */
        if($setting=$this->em->getRepository(Setting::class)->findByName($name)) {
            $this->settings[$name]=$setting->toArray();
            return $setting->toArray();
        }

        return null;
    }

    /**
     * returns array with all options
     *
     * @return array
     */
    public function getAll() {
        $data=[];

        foreach($this->settings AS $name=>$value) {
            $data[$name]=$this->get($name);
        }

        return $data;
    }

    /**
     * returns array with all options and additional data
     *
     * @return array
     */
    public function getAllFull() {
        $data=[];

        foreach($this->settings AS $name=>$value) {
            $data[$name]=$this->getFull($name);
        }

        return $data;
    }

    /**
     * returns array with all values from a given section
     *
     * @param $section
     * @return array
     */
    public function getSection($section) {
        $data=[];

        foreach($this->settings AS $name=>$settingData) {
            if($section===$settingData['section']) {
                $data[$name]=$this->get($name);
            }
        }

        return $data;
    }

    /**
     * returns array with all values with additional data from a given section
     *
     * @param $section
     * @return array
     */
    public function getSectionFull($section) {
        $data=[];

        foreach($this->settings AS $name=>$settingData) {
            if($section===$settingData['section']) {
                $data[$name]=$this->getFull($name);
            }
        }

        return $data;
    }

    /**
     * sets an option
     *
     * @param $name
     * @param $value
     * @param string $type
     * @param string $section
     * @throws \Exception
     */
    public function set($name, $value, $type=null, $section=null) {

        if($type===null) {
            $type=SettingInterface::TYPE_STRING;
        }

        if(!$setting=$this->em->getRepository(Setting::class)->findByName($name)) {
            $setting=new Setting();
            $setting->setName($name);
        }

        $setting->setValue($this->transformValueToDatabase($value,$type));
        $setting->setSection($section);
        $setting->setType($type);
        $setting->setUpdatedAt(new \DateTime('now'));
        $this->em->persist($setting);
        $this->em->flush();

        $this->addToCache($setting);
    }

    /**
     * fetches all options to cache
     */
    public function fetchAll() {

        $settings=$this->em->getRepository(Setting::class)->findAll();

        /** @var Setting $setting */
        foreach($settings AS $setting) {
            $this->addToCache($setting);
        }
    }

    /**
     * adds a setting to the cache
     *
     * @param Setting $setting
     */
    protected function addToCache(Setting $setting) {
        $this->settings[$setting->getName()]=$setting->toArray();
    }

    /**
     * transforms value to string-representation for the database (e.g. array to json)
     *
     * @param $value
     * @param $type
     * @return int|string
     * @throws \Exception
     */
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

    /**
     * transforms string-representation from database to php-objects (e.g. json to array)
     *
     * @param $value
     * @param $type
     * @return bool|\DateTime|int|mixed|string
     */
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
            }
            if(in_array($value,[false,0,'n','no','false'])) {
                return false;
            }
        }

        if($type==SettingInterface::TYPE_DATE_TIME) {
            return \DateTime::createFromFormat('Y-m-d H:i:s',$value);
        }

        if($type==SettingInterface::TYPE_ARRAY) {
            return json_decode($value,true);
        }

        return $value;
    }
}
