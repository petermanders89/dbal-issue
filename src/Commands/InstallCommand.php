<?php

declare(strict_types=1);

namespace App\Commands;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:install', description: 'Prepares all databases and components for use')]
class InstallCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $symfonyStyle = new SymfonyStyle($input, $output);

        $symfonyStyle->note('Installing Application...');

        $this->getApplication()->setAutoExit(false);

        $symfonyStyle->comment('Creating database');

        $this->getApplication()->run(new StringInput('doctrine:migrations:migrate --no-interaction'), $output);

        $symfonyStyle->comment('Created post');

        $post = new Post();
        $post->setTitle('This is a new post');

        $this->entityManager->persist($post);
        $this->entityManager->flush();

        return 0;
    }
}
