<?php

namespace Creavo\OptionBundle\Provider;

use Creavo\OptionBundle\Interfaces\SettingInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Cache\Simple\AbstractCache;
use Symfony\Component\Cache\Simple\ArrayCache;
use Webmozart\Assert\Assert;

class Settings {

    /** @var AbstractCache */
    protected $cache;

    /** @var ObjectManager */
    protected $em;

    public function __construct(RegistryInterface $registry, AbstractCache $cache=null) {
        $this->em=$registry->getManager();
        $this->setCache($cache!==null ? $cache : new ArrayCache());
    }

    public function setCache(AbstractCache $cache) {
        $this->cache=$cache;
    }

    public function get($name) {

        return $name;
    }

    public function getAll() {

    }

    public function getSection($section) {

    }

    public function set($name, $value, $type=SettingInterface::TYPE_STRING) {

    }

    protected function transformValueToDatabase($value,$type) {

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
