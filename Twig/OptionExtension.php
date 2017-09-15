<?php

namespace Creavo\OptionBundle\Twig;


class OptionExtension extends \Twig_Extension
{

    public function __construct() {

    }

    public function getFunctions() {
        return [
            new \Twig_SimpleFunction('crv_ob_setting',[$this,'getSetting']),
        ];
    }

    public function getSetting($value) {

    }

}
