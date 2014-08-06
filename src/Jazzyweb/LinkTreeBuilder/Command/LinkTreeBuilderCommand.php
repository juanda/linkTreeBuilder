<?php

namespace Jazzyweb\LinkTreeBuilder\Command;

use Jazzyweb\LinkTreeBuilder\Builder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class LinkTreeBuilderCommand extends Command{

    public function configure(){
        $this
            ->setName('ltb:build')
            ->setDescription('Build a tree of links from a given url')
            ->addArgument(
                'link',
                InputArgument::REQUIRED,
                'Introduce link to follow:'
            )
            ->addOption(
                'depth',
                null,
                InputOption::VALUE_OPTIONAL,
                'maximum tree depth (default 3)',
                3
            )
            ->addOption(
                'stop',
                null,
                InputOption::VALUE_NONE,
                'If set this condition will be check as stop condition'
            )
            ->addOption(
                'title',
                null,
                InputOption::VALUE_OPTIONAL,
                'Name of the root node (default ROOT)',
                'ROOT'
            )
            ->addOption(
                'dump',
                null,
                InputOption::VALUE_NONE,
                'If set the nodes are being dumped during building process'
            )
            ->addOption(
                'timeout',
                null,
                InputOption::VALUE_OPTIONAL,
                'request timeout (defaul 1)',
                1
            )
            ->addOption(
                'out',
                null,
                InputOption::VALUE_OPTIONAL,
                'out file name (defaul out)',
                1
            )
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output){

        $title   = $input->getOption("title");
        $link    = $input->getArgument("link");

        $depth   = $input->getOption("depth");
        $stop    = $input->getOption("stop");
        $dump    = $input->getOption("dump");
        $timeout = $input->getOption("timeout");
        $out     = $input->getOption("out");

        $builder = new Builder($title, $link, $depth, $stop, $dump, $timeout);

        $tree = $builder->build();
        $treeAsArray = $tree->toArray();
//        print_r($tree);
//        print_r($tree->toArray());

        $yaml = Yaml::dump($treeAsArray);
        $json = json_encode($treeAsArray);

//        print_r($yaml);
        echo PHP_EOL;
        $fd = fopen($out.'.yml', 'w');
        fwrite($fd, $yaml);
        $output->writeln('<info>Results in yaml format in ' . $out . '.yml</info>');
        fclose($fd);

        $fd = fopen($out.'.json', 'w');
        fwrite($fd, $json);
        $output->writeln('<info>Results in json format in ' . $out . '.json</info>');
        fclose($fd);
    }

}