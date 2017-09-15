<?php

namespace Creavo\OptionBundle\Interfaces;

interface SettingInterface
{

    const TYPE_STRING=1;
    const TYPE_INTEGER=2;
    const TYPE_DATE_TIME=3;
    const TYPE_ARRAY=4;

    public function setName($name);
    public function getName();

    public function setValue($value);
    public function getValue();

    public function setSection($value);
    public function getSection();

    public function setType($type);
    public function getType();

    public function setUpdatedAt($updatedAt);
    public function getUpdatedAt();
}
