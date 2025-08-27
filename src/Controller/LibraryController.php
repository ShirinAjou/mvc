<?php

namespace App\Controller;

use App\Entity\Book;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller for handling library-related routes.
 */
final class LibraryController extends AbstractController
{
    /**
     * Renders the library index page.
     *
     * @return Response The rendered index page.
     */
    #[Route('/library', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('library/index.html.twig', [
            'controller_name' => 'LibraryController',
        ]);
    }

    /**
     * Handles the creation of a new book.
     *
     * @param Request $request The HTTP request.
     * @param ManagerRegistry $doctrine The doctrine manager registry.
     * @return Response The rendered create book page.
     */
    #[Route('/library/create', name: 'book_create', methods: ['GET', 'POST'])]
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

    /**
     * Displays all books in the library.
     *
     * @param BookRepository $bookRepository The repository for accessing book data.
     * @return Response The rendered book page.
     */
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

    /**
     * Displays a book by its id.
     *
     * @param BookRepository $bookRepository The repository for accessing book data.
     * @param int $id The id of the book to display.
     * @return Response The rendered book id page.
     */
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

    /**
     * Deletes a book by its id.
     *
     * @param ManagerRegistry $doctrine The doctrine manager registry.
     * @param int $id The id of the book to delete.
     * @return Response The rendered delete book id page.
     */
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

    /**
     * Updates a book by its id.
     *
     * @param ManagerRegistry $doctrine The doctrine manager registry.
     * @param int $id The id of the book to update.
     * @param Request $request The HTTP request.
     * @return Response The rendered updated book id page.
     */
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
