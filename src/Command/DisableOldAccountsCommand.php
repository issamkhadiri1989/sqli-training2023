<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

class DisableOldAccountsCommand extends Command
{
    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('app:accounts:disable')
            ->setDescription('Disables all accounts that have not been used since 1 year now.')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        // do something
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        // do something
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $styler = new SymfonyStyle($input, $output);
        $styler->title('Accounts Disabler');
        /*$styler->section('Find and disable all accounts that have not been used for 1 year now');*/
        $styler->block('Find and disable all accounts that have not been used for 1 year now');
        $styler->info('Command launched at '.(new \DateTimeImmutable())->format('d/m/Y H:i:s'));
        /*$styler->text('text');*/

        try {
            $inactiveAccounts = $this->manager
                ->getRepository(User::class)
                ->retrieveOldAccounts(365);
        } catch (\Exception) {
            $styler->error('The command has stopped unexpectedly');

            return self::FAILURE;
        }

        if (($count = \count($inactiveAccounts)) > 0) {
            $styler->text(\sprintf('<info>%d</info> accounts found to delete', $count));
            $this->renderAllAccountsToDelete($output, $inactiveAccounts);
            $answer = $this->askUserPermissionToDelete($input, $output);

            if (true === $answer) {
                $this->performDisable($output, $inactiveAccounts);
                $styler->success('Operation ended.');
            } else {
                $styler->info('Ok, operation canceled.');
            }
        } else {
            $styler->success('No accounts to disable');
        }

        return self::SUCCESS;
    }

    /**
     * Disables all accounts.
     */
    private function performDisable(OutputInterface $output, array $accounts): void
    {
        $progress = new ProgressBar($output);
        $progress->setMaxSteps(\count($accounts));
        /** @var User $account */
        foreach ($accounts as $account) {
            $progress->advance();
            \sleep(1);
            $account->setEnabled(false);
        }
        $progress->finish();
        $this->manager->flush();
    }

    /**
     * Asks the user for his permission to whether or not continue the operation.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return bool
     */
    private function askUserPermissionToDelete(InputInterface $input, OutputInterface $output): bool
    {
        $questionHelper = $this->getHelper('question');
        $question = new ConfirmationQuestion(
            'The above accounts won\'t be able to login after this operation, are you sure to continue ?',
            false,
            '/^yes$/'
        );

        return $questionHelper->ask($input, $output, $question);
    }

    /**
     * Renders a nice table containing all the accounts that will be disabled.
     *
     * @param OutputInterface $output
     * @param array $accounts
     *
     * @return void
     */
    private function renderAllAccountsToDelete(OutputInterface $output, array $accounts)
    {
        $helper = new Table($output);
        $helper->setHeaders(['ID', 'USERNAME', 'FULL NAME', 'LAST CONNECTION']);
        foreach ($accounts as $inactiveAccount) {
            $helper->addRow([
                $inactiveAccount->getId(),
                $inactiveAccount->getUsername(),
                $inactiveAccount->getFullName(),
                $inactiveAccount->getLastConnectionTime()->format('F, d Y H:i'),
            ]);
        }

        $helper->render();
    }
}
