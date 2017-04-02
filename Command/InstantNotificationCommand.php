<?php

namespace Notification\InstantNotificationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class InstantNotificationCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('notification:instant')
            ->addArgument('arguments', InputArgument::OPTIONAL);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('arguments');

        if ($name) {

            switch ($name) {
                case 'start':
                    $this->doAction($output, $name);
                    break;

                case 'stop':
                    $this->doAction($output, $name);
                    break;

                case 'restart':
                    $this->doAction($output, $name);
                    break;

                default:
                    $response = $this->render_usage();
                    $output->writeln($response);
            }

        } else {

            $response = $this->render_usage();

            $output->writeln($response);

        }
    }

    protected function render_usage()
    {

        $text = <<<EOD
                
    USAGE
      bin/console {$this->getName()} [action] [parameter]

    DESCRIPTION
      This command provides support for managing instant notifications system.

    EXAMPLES
     * bin/console {$this->getName()} start
       Start RSMQ REST server and RSMQ worker client.

     * bin/console {$this->getName()} stop
       Stop RSMQ REST server and RSMQ worker client.

     * bin/console {$this->getName()} restart
       Restart RSMQ REST server and RSMQ worker client.
                        
EOD;

        return $text;
    }

    protected function doAction(OutputInterface $output, $action)
    {
        $command = $this->getApplication()->find('rsmq:rest');

        $arguments = array(
            'command' => 'rsmq:rest',
            'arguments' => $action
        );

        $input = new ArrayInput($arguments);
        $command->run($input, $output);

        $command = $this->getApplication()->find('rsmq:worker');

        $arguments = array(
            'command' => 'rsmq:rest',
            'arguments' => $action
        );

        $input = new ArrayInput($arguments);
        $command->run($input, $output);
    }
}
