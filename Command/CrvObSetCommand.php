<?php

namespace Creavo\OptionBundle\Command;

use Creavo\OptionBundle\Entity\Setting;
use Creavo\OptionBundle\Interfaces\SettingInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CrvObSetCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('crv:ob:set')
            ->setDescription('sets a value')
            ->addArgument('name', InputArgument::REQUIRED, 'name of the setting')
            ->addArgument('value', InputArgument::REQUIRED, 'value of the setting')
            ->addArgument('type', InputArgument::OPTIONAL, 'type of the setting')
            ->addArgument('section', InputArgument::OPTIONAL, 'section this option belongs to')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $value = $input->getArgument('value');
        $type = $input->getArgument('type');
        $section = $input->getArgument('section');

        if($type==SettingInterface::TYPE_DATE_TIME) {
            $value=new \DateTime($value);
        }elseif($type==SettingInterface::TYPE_ARRAY) {
            $value=json_decode($value,true);
        }

        if(Setting::getIdByTypeName($type)) {
            $type=Setting::getIdByTypeName($type);
        }

        $this->getContainer()->get('creavo_option.settings')->set($name,$value,$type,$section);
    }

}
