<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'CreateUser',
    description: 'Create User and admin',
)]
class CreateUserCommand extends Command
{
    private ManagerRegistry $managerRegistry;
    private UserPasswordHasherInterface $hasher;

    public function __construct(ManagerRegistry $managerRegistry, UserPasswordHasherInterface $hasher){
        $this->managerRegistry = $managerRegistry;
        $this->hasher = $hasher;
        parent::__construct(); 
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }

        $helper = $this->getHelper('question');
        $qUseremail = new Question('Please enter the user email : ', '');
        $userEmail = $helper->ask($input, $output, $qUseremail);
        $qUserpass = new Question('Please enter the password : ', '');
        $userPass = $helper->ask($input, $output, $qUserpass);
        $question = new ChoiceQuestion(
            'Please select the roles for this user (default ROLE_USER)',
            ['ROLE_ADMIN', 'ROLE_USER'],
            '1'
        );
        $question->setMultiselect(true);
        $roles = $helper->ask($input, $output, $question);

        $em = $this->managerRegistry->getManager();
        $userRepo = $this->managerRegistry->getRepository(User::class);

        $user = new User();
        $user->setEmail($userEmail);
        $user->setPassword($userPass);
        $this->hashUserPassword($user);
        if(is_array($roles) && !empty($roles)){
            $user->setRoles($roles);
        }
        $em->persist($user);
        $em->flush();

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }

    public function hashUserPassword(User $user)
    {
        $currentPasswd = $user->getPassword();
        $startStr = substr($currentPasswd,0,7);
        if (strlen($currentPasswd)>0 && $startStr!='$2y$13$'){
            $hashedPassword = $this->hasher->hashPassword(
                $user,
                $user->getPassword()
            );
            $user->setPassword($hashedPassword);
        }

    }
}