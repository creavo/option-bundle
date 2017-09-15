<?php

namespace Creavo\OptionBundle\Command;

use Creavo\OptionBundle\Entity\Setting;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

class CrvObGetCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('crv:ob:get')
            ->setDescription('gets a value')
            ->addArgument('name', InputArgument::REQUIRED, 'name of the setting')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');

        $data=$this->getContainer()->get('creavo_option.settings')->getFull($name);

        if($data===null) {
            $output->writeln('setting "'.$name.'" is not set');
            return;
        }

        $table=new Table($output);
        $table
            ->setHeaders(['Element','Value'])
            ->setRows([
                ['name',$data['name']],
                ['type',Setting::getTypeName($data['type'])],
                ['section',$data['section']],
                ['updatedAt',$data['updatedAt']->format('Y-m-d H:i:s')],
                ['value',$data['value']],
            ])
        ;
        $table->render();
    }

}
