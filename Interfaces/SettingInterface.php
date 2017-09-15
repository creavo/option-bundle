<?php

namespace Creavo\OptionBundle\Interfaces;

interface SettingInterface
{

    const TYPE_STRING=1;
    const TYPE_INTEGER=2;
    const TYPE_DATE_TIME=3;
    const TYPE_ARRAY=4;

    function setName($name);
    function getName();

    function setValue($value);
    function getValue();

    function setSection($value);
    function getSection();

    function setType($type);
    function getType();

    function setUpdatedAt($updatedAt);
    function getUpdatedAt();
}