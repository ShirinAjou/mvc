<?php

namespace App\Controller;

use App\Entity\Book;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class LibraryController extends AbstractController
{
    #[Route('/library', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('library/index.html.twig', [
            'controller_name' => 'LibraryController',
        ]);
    }

    #[Route('/library/create', name: 'book_create', methods: ["GET", "POST"])]
    public function createBook(Request $request, ManagerRegistry $doctrine): Response
    {
        if ($request->isMethod('POST')) {
            $title = $request->request->get('title');
            $isbn = $request->request->get('isbn');
            $author = $request->request->get('author');
            $image = $request->request->get('image');

            $book = new Book();
            $book->setTitle((string) $title);
            $book->setIsbn((string) $isbn);
            $book->setAuthor((string) $author);
            $book->setImage((string) $image);

            $entityManager = $doctrine->getManager();
            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('book_show_all');
        }
        return $this->render('library/create.html.twig');
    }

    #[Route('/library/show', name: 'book_show_all')]
    public function showAllBook(
        BookRepository $bookRepository
    ): Response {
        $books = $bookRepository
            ->findAll();
        $response = $this->json($books);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $this->render('library/show.html.twig', [
            'books' => $books,
        ]);
    }

    #[Route('/library/show/{id}', name: 'book_by_id')]
    public function showBookById(
        BookRepository $bookRepository,
        int $id
    ): Response {
        $book = $bookRepository
            ->find($id);

        return $this->render('library/book_show.html.twig', [
            'book' => $book,
        ]);
    }

    #[Route('/library/delete/{id}', name: 'book_delete_by_id')]
    public function deleteBookById(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Book::class)->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$id
            );
        }
        $entityManager->remove($book);
        $entityManager->flush();

        return $this->redirectToRoute('book_show_all');
    }

    #[Route('/library/update/{id}', name: 'book_update')]
    public function updateBook(ManagerRegistry $doctrine, int $id, Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Book::class)->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$id
            );
        }

        if ($request->isMethod('POST')) {
            $title = $request->request->get('title');
            $isbn = $request->request->get('isbn');
            $author = $request->request->get('author');
            $image = $request->request->get('image');

            if ($title === null) {
                return $this->redirectToRoute('book_update', ['id' => $id]);
            }

            $book->setTitle((string) $title);
            $book->setIsbn((string) $isbn);
            $book->setAuthor((string) $author);
            $book->setImage((string) $image);
            $entityManager->flush();

            return $this->redirectToRoute('book_show_all');
        }

        return $this->render('library/update.html.twig', [
            'book' => $book,
        ]);
    }
}
