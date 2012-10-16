<?php

namespace Liip\RD\Prerequisite;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

use Liip\RD\VCS\VCSInterface;
use Liip\RD\Context;
use Liip\RD\Information\InformationRequest;


class WorkingCopyCheck extends BasePrerequisite {

    public $ignoreCheckOptionName = 'ignore-check';

    public function getTitle()
    {
        return 'Check that your working copy is clean';
    }

    public function execute($context)
    {
        if ($context->getService('information-collector')->getValueFor($this->ignoreCheckOptionName)){
            $context->getService('output')->writeln('<error>requested to be ignore</error>');
            return;
        }

        if (count($modif = $context->getService('vcs')->getLocalModifications()) > 0){
            throw new \Exception('Your working directory contain local modifications, use --'.$this->ignoreCheckOptionName.' option to bypass this check');
        }
        
        $this->confirmSuccess($context);
    }

    public function getInformationRequests()
    {
        return array(
            new InformationRequest($this->ignoreCheckOptionName, array(
                'description' => 'Do not process the check for clean working copy',
                'type' => 'boolean',
                'default' => false
            ))
        );
    }
}