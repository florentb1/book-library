<?php

namespace App\Controller;

use App\Elasticsearch\BooksLibraryElasticsearch;
use App\Manager\ElasticsearchManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LibraryController extends AbstractController
{
    CONST ALIAS = 'library';

    /**
     * @var ElasticsearchManager
     */
    private $elasticsearchManager;

    private $booksLibraryElasticsearch;

    public function __construct(
        ElasticsearchManager $elasticsearchManager,
        BooksLibraryElasticsearch $booksLibraryElasticsearch)
    {
        $this->booksLibraryElasticsearch = $booksLibraryElasticsearch;
        $this->elasticsearchManager = $elasticsearchManager;
    }

    // TODO: ADD PAGINATION
    #[Route('/api/books', name: 'api-books', methods: ['GET'])]
    public function findAllBooks()
    {
        $books = $this->booksLibraryElasticsearch->findAllBooks(self::ALIAS);

        $books = $this->elasticsearchManager->getHits($books);

        return $this->json($books);
    }

    // TODO: ADD PAGINATION
    #[Route('/api/books/filter', name: 'api-books-filter', methods: ['GET'])]
    public function findBooksByFilter(Request $request)
    {
        $filterType = $request->query->get('filterType', null);
        $filterValue =$request->query->get('filterValue', null);

        if ($filterType === null || $filterValue === null) {
            return $this->json([]);
        }

        $books = $this->booksLibraryElasticsearch->findBooksByFilter(self::ALIAS, $filterType, $filterValue);

        if ($this->elasticsearchManager->isResponseSuccess($books)) {
            $books = $this->elasticsearchManager->getHits($books);

            return $this->json($books);
        }

        return $this->json([]);
        
    }

}
