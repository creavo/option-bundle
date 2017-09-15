<?php

namespace Creavo\OptionBundle\Interfaces;

interface OptionInterface
{

    function setName($name);
    function getName();

    function setValue($value);
    function getValue();

    function setSection($value);
    function getSection();

    function setType($type);
    function getType();

}