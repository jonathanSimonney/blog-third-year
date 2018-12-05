<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:create-user';
    private $objectManager;
    private $passwordEncoder;

    public function __construct(ObjectManager $objectManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->objectManager = $objectManager;
        $this->passwordEncoder = $passwordEncoder;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Creates a new user.')
            ->setHelp('This command allows you to create a user...')
            ->addArgument('userMail', InputArgument::REQUIRED, 'The email of the user.')
            ->addArgument('role', InputArgument::REQUIRED, 'The role of the user.')
            ->addArgument('password', InputArgument::REQUIRED, 'The role of the user.')
//            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'User Creator',
            '============',
            '',
        ]);

        $user = new User();

        $user->setEmail($input->getArgument('userMail'));
        $user->setPassword($this->passwordEncoder->encodePassword($user, $input->getArgument('password')));
        $user->setRoles([$input->getArgument('role')]);

        // retrieve the argument value using getArgument()
       $this->objectManager->persist($user);
        $this->objectManager->flush();

        $output->writeln([
            'User Created',
            '============',
            '',
        ]);
//        $io = new SymfonyStyle($input, $output);
//        $arg1 = $input->getArgument('arg1');
//
//        if ($arg1) {
//            $io->note(sprintf('You passed an argument: %s', $arg1));
//        }
//
//        if ($input->getOption('option1')) {
//            // ...
//        }
//
//        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
    }
}
