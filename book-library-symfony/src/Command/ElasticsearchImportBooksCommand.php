<?php

namespace App\Command;

use App\Elasticsearch\BooksLibraryElasticsearch;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ElasticsearchImportBooksCommand extends Command
{

    CONST ALIAS = 'library';

    protected static $defaultName = 'elasticsearch:import-books';
    protected static $defaultDescription = 'Chargement de la base de donnée Elasticsearch';

    private $booksLibraryElasticsearch;
    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    private $libaryFilePath;

    public function __construct(BooksLibraryElasticsearch $booksLibraryElasticsearch, ParameterBagInterface $parameterBag)
    {
        parent::__construct(self::$defaultName);
        $this->booksLibraryElasticsearch = $booksLibraryElasticsearch;
        $this->parameterBag = $parameterBag;
        $this->libraryFilePath = $this->parameterBag->get('kernel.project_dir') .
            '/public/data/bibliotheque.csv';
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $start = microtime(true); // Mesure time

        $io = new SymfonyStyle($input, $output);

        $output->writeln('CREATION DE l\'INDEX ELASTICSEARCH');

        $index = $this->createElasticSearchIndex($io);


        $output->writeln('ALIMENTATION DE LA BASE ELASTICSEARCH');

        $this->feedElasticsearch($index, $io);


        $output->writeln('BASCULEMENT DES ALIAS SUR LE NOUVEL INDEX');

        $this->setIndexInProduction($index);

        $time_elapsed_secs = microtime(true) - $start;

        $io->success('IMPORT EFFECTUEE. L\'IMPORT A PRIS ' . round($time_elapsed_secs, 2) . ' SECONDES');

        return Command::SUCCESS;
    }



    protected function createElasticSearchIndex(SymfonyStyle $io)
    {

        $newIndex = 'library_' . date('Ymd');

        if ($this->booksLibraryElasticsearch->checkIfIndexExists($newIndex)) {
            $errorMessage = 'L\'INDEX EXISTE DEJA. ARRET DU TRAITEMENT';
            $this->handleError($errorMessage, $io);
        }

        $index = $this->booksLibraryElasticsearch->createIndex($newIndex); // création de l'index Elasticsearch

        if ($index === false) {
            $errorMessage = 'LA GENERATION DE L\'INDEX A ECHOUE. ARRET DU TRAITEMENT';
            $this->handleError($errorMessage, $io);
        }

        return $index['index'];
    }


    protected function feedElasticsearch($index, SymfonyStyle $io)
    {

        if (!$libraryFile = fopen($this->libraryFilePath, 'r')) {
            $errorMessage = 'LE FICHIER DE DONNEES N\'A PAS ETE TROUVE';
            $this->handleError($errorMessage, $io);
        }

        $i = 0;
        $params = ['body' => []];

        while (($bookInformations = fgetcsv($libraryFile, 1000, ";")) !== FALSE) {

            $i++;

            if ($i === 1) {
                continue;
            }

            $params = $this->addBookInParams($index, $params, $bookInformations, $i);

            if ($i % 1000 == 0) {
                $response = $this->booksLibraryElasticsearch->bulkQuery($params);

                if ($response === false) {

                    $io->warning('ECHEC D\'UNE REQUETE');
                }

                $params = ['body' => []];
            }
        }

        if (!empty($params['body'])) {
            $response = $this->booksLibraryElasticsearch->bulkQuery($params);

            if ($response === false) {

                $io->warning('ECHEC D\'UNE REQUETE');
            }
        }
    }

    protected function addBookInParams($index, $params, $bookInformations, $i)
    {
        $params['body'][] = [
            'index' => [
                '_index' => $index,
                '_id'    => $i
            ]
        ];

        $params['body'][] = [ 
                'title' => $bookInformations[0],
                'author' => $bookInformations[1],
                'releaseYear' => $bookInformations[2],
                'type' => $bookInformations[3] 
            ];

        return $params;
    }


    protected function setIndexInProduction($index)
    {
        $aliases = $this->booksLibraryElasticsearch->getAliases($index);

        $aliases = $aliases[$index];

        if (empty($aliases) || (!isset($aliases[self::ALIAS]))) {
            $this->booksLibraryElasticsearch->createAlias($index, self::ALIAS);
        }
    }


    protected function handleError($errorMessage, SymfonyStyle $io)
    {
        $io->error($errorMessage);

        die;
    }


}
