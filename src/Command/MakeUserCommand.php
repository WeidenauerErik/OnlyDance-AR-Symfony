<?php

namespace App\Command;

use App\Entity\DanceSchool;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'create:user',
    description: 'Create a user or admin account'
)]
class MakeUserCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserPasswordHasherInterface $hasher
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('type', InputArgument::REQUIRED, 'Type: "admin" or "user"')
            ->addArgument('email', InputArgument::REQUIRED, 'Email address')
            ->addArgument('password', InputArgument::REQUIRED, 'Password');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $type = strtolower($input->getArgument('type'));
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        $roles = match ($type) {
            'admin' => ['ROLE_ADMIN'],
            'user' => ['ROLE_USER'],
            default => null,
        };

        if ($roles === null) {
            $output->writeln('<error>Invalid type. Use "admin" or "user".</error>');
            return Command::FAILURE;
        }

        $user = new User();
        $user->setEmail($email);
        $user->setRoles($roles);
        $user->setPassword($this->hasher->hashPassword($user, $password));

        $this->em->persist($user);
        $this->em->flush();

        $output->writeln("User <{$email}> created with role: " . implode(', ', $roles));

        return Command::SUCCESS;
    }
}
