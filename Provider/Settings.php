<?php

namespace Creavo\OptionBundle\Provider;

use Creavo\OptionBundle\Interfaces\SettingInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Cache\Simple\AbstractCache;
use Symfony\Component\Cache\Simple\ArrayCache;

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

    }

    public function getAll() {

    }

    public function getSection($section) {

    }

    public function set($name, $value, $type=SettingInterface::TYPE_STRING) {

    }

    protected function transformValueToDatabase($value,$type) {

    }

    protected function transformValueFromDatabase($value,$type) {

    }
}
