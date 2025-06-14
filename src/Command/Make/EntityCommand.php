<?php

namespace App\Command\Make;

use App\Enum\EntityManagers;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:make:entity',
    description: 'Replace default make entity command with custom one for multiple databases',
)]
class EntityCommand extends Command
{
    private Application $application;

    public function setApplication(?Application $application): void
    {
        parent::setApplication($application);
        $this->application = $application;
    }

    public function __construct()
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Génère une entité Doctrine pour un EntityManager spécifique.')
            ->setHelp('Cette commande vous permet de choisir un EntityManager et de générer une entité pour celui-ci.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // On demande le nom de l'entité à créer
        $entityName = $io->ask('Veuillez entrer le nom de l\'entité à créer (ex: Produit)');
        if (empty($entityName)) {
            $io->error('Le nom de l\'entité ne peut pas être vide.');
            return Command::FAILURE;
        }

        $entityManagers = EntityManagers::values();

        $question = new ChoiceQuestion(
            'Veuillez choisir un EntityManager pour générer l\'entité :',
            array_values($entityManagers)
        );
        $question->setErrorMessage('EntityManager invalide.');

        $chosenManager = $io->askQuestion($question);

        // Trouver la clé de l'EntityManager choisi
        $managerKey = array_search($chosenManager, $entityManagers);

        if ($managerKey === false) {
            $io->error('EntityManager non reconnu.');
            return Command::FAILURE;
        }

        $relativePath = sprintf('%s\\%s', ucfirst($managerKey), $entityName);

        $command = $this->application->find('make:entity');
        $arguments = new ArrayInput([
            'command' => 'make:entity',
            'name' => $relativePath,
        ]);

        // Appelle la commande `make:entity` par défaut avec le path relatif
        return $command->run($arguments, $output);
    }
}
