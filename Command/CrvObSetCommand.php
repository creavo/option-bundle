<?php

namespace Creavo\OptionBundle\Command;

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
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $value = $input->getArgument('value');

        $this->getContainer()->get('creavo_option.settings')->set($name,$value);
    }

}
