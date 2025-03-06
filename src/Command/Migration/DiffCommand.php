<?php

namespace App\Command\Migration;

use App\Enum\EntityManagers;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

#[AsCommand(
    name: 'app:migrations:diff',
    description: 'Replace default diff command with custom one for multiple databases',
)]
class DiffCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Génère une migration pour un EntityManager spécifique.')
            ->setHelp('Cette commande vous permet de choisir un EntityManager et de générer une migration pour celui-ci.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $entityManagers = EntityManagers::values();

        $question = new ChoiceQuestion(
            'Veuillez choisir un EntityManager pour générer la migration :',
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

        // Exécuter la commande de migration correspondante
        $command = sprintf(
            'php bin/console doctrine:migrations:diff --em=%s --configuration=config/packages/migrations/%s.yaml --no-interaction',
            $managerKey,
            $managerKey
        );

        $io->note(sprintf('Exécution de la commande : %s', $command));

        $process = Process::fromShellCommandline($command);
        $process->setTimeout(null);
        $process->run(function ($type, $buffer) use ($io) {
            $io->write($buffer);
        });

        if ($process->isSuccessful()) {
            $io->success('Migrations exécutées avec succès.');
            return Command::SUCCESS;
        } else {
            $io->error('Erreur lors de l\'exécution des migrations.');
            return Command::FAILURE;
        }
    }
}
