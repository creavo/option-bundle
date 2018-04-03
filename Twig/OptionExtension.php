<?php

namespace Creavo\OptionBundle\Twig;

use Creavo\OptionBundle\Provider\Settings;

class OptionExtension extends \Twig_Extension
{

    /** @var Settings */
    protected $settings;

    public function __construct(Settings $settings) {
        $this->settings=$settings;
    }

    public function getFunctions() {
        return [
            new \Twig_SimpleFunction('crv_ob_setting',[$this,'getSetting']),
        ];
    }

    public function getSetting($value) {
        return $this->settings->get($value);
    }

}
