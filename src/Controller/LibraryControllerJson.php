<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LibraryControllerJson extends AbstractController
{
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
