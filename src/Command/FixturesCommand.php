<?php

namespace App\Command;

use App\Enum\EntityManagers;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

#[AsCommand(
    name: 'app:fixtures',
    description: 'Execute fixtures',
)]
class FixturesCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Exécute les fixtures pour peupler les bases de données')
            ->setHelp('Cette commande permet d\'exécuter les fixtures pour peupler les bases de données');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $entityManagers = EntityManagers::values();

        $errors = [];
        foreach ($entityManagers as $em => $value) {
            $io->section(sprintf('Entity Manager : %s', $em));

            $error = $this->executeFixtures($io, $em);
            if (!empty($error)) {
                $errors[] = $error;
            }
        }

        if (!empty($errors)) {
            $io->error('Erreurs lors de l\'exécution des fixtures :');
            foreach ($errors as $error) {
                $io->error($error);
            }

            return Command::FAILURE;
        }

        $io->success('Fixtures exécutées avec succès.');
        return Command::SUCCESS;
    }

    private function executeFixtures(SymfonyStyle $io, string $em): string
    {
        $command = sprintf('php bin/console doctrine:fixtures:load --group=%s --em=%s --no-interaction', $em, $em);

        $io->note(sprintf('Exécution de la commande : %s', $command));

        $process = Process::fromShellCommandline($command);
        $process->setTimeout(null);
        $process->run(function ($type, $buffer) use ($io) {
            $io->write($buffer);
        });

        if ($process->isSuccessful()) {
            $io->success('Fixtures exécutées avec succès pour l\'Entity Manager ' . $em);
        } else {
            $io->error('Erreur lors de l\'exécution des fixtures pour l\'Entity Manager ' . $em);
        }

        return $process->getErrorOutput();
    }
}
