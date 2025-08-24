<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Controller for handling JSON responses related to the library.
 */
class LibraryControllerJson extends AbstractController
{
    /**
     * Returns a JSON response with all books in the library.
     *
     * @param BookRepository $bookRepository The repository for accessing book data.
     * @return Response A JSON response with all books in the library.
     */
    #[Route('api/library/books', name: 'api_book')]
    public function bookJson(BookRepository $bookRepository): Response
    {
        $books = $bookRepository
            ->findAll();

        $response = $this->json($books);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    /**
     * Returns a JSON response with a book's details by its ISBN.
     *
     * @param BookRepository $bookRepository The repository for accessing book data.
     * @param string $isbn The ISBN of the book.
     * @return Response A JSON response with the book's details.
     */
    #[Route('api/library/book/{isbn}', name: 'api_book_isbn')]
    public function isbnJson(BookRepository $bookRepository, string $isbn): Response
    {
        $book = $bookRepository
            ->findOneBy(['isbn' => $isbn]);

        $response = $this->json($book);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
