<?php

namespace App\Command;

use App\Model\ParserModel;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ParserNewsCommand extends Command
{
    private ParameterBagInterface $parameterBag;
    private ParserModel $model;

    public function __construct(ParameterBagInterface $parameterBag, ParserModel $model, string $name = null)
    {
        $this->parameterBag = $parameterBag;
        $this->model = $model;
        parent::__construct($name);
    }

    protected static $defaultName = 'app:parser-news';
    private CONST NAME_PARAM_HOST = 'host';

    protected function configure(): void
    {
        $this
            ->setHelp('Консольная команда для загрузки новостей')
            ->addArgument(
                self::NAME_PARAM_HOST,
                InputArgument::REQUIRED,
                'Ключ в конфиге для указания сайта с которого нужно загрузить новости'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $host = $input->getArgument(self::NAME_PARAM_HOST);
        $config = $this->parameterBag->get('hosts');
        if (!array_key_exists($host, $config)) {
            $output->writeln('undefined name site in config');
            return  Command::INVALID;
        }

        $result = $this->model->parseAndSaveData($config[$host]);
        $output->writeln($result);
        return Command::SUCCESS;
    }
}
