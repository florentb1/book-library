<?php

namespace App;

class Library
{
    private $books;

    public function __construct()
    {
        $this->books = [];
    }

    /**
     * @return mixed
     */
    public function getBooks()
    {
        return $this->books;
    }

    public function addBook(Book  $book)
    {
        $this->books[] = $book;

        return $this;
    }


}