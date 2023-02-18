<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class BookController
 *@author  Tresor-ilunga <19im065@esisalama.org>
 */
class BookController extends AbstractController
{
    /**
     * The function that allows you to retrieve all the books
     *
     * @param BookRepository $bookRepository
     * @param SerializerInterface $serializerInterface
     * @return JsonResponse
     */
    #[Route('/api/books', name: 'book', methods: ['GET'])]
    public function getAllBooks(BookRepository $bookRepository, SerializerInterface $serializerInterface): JsonResponse
    {
        $bookList = $bookRepository->findAll();

        $jsonBookList = $serializerInterface->serialize($bookList, 'json', ['groups' => 'getBooks']);
        return new JsonResponse($jsonBookList, Response::HTTP_OK, [], true);
    }

    /**
     * The function that allows you to retrieve a book by id
     * @param Book $book
     * @param SerializerInterface $serializerInterface
     * @return JsonResponse
     * @author  Tresor-ilunga <19im065@esisalama.org>
     */
    #[Route('/api/books/{id}', name: 'book_detail', methods: ['GET'])]
    public function getDetailBook(Book $book, SerializerInterface $serializerInterface): JsonResponse
    {
        $jsonBook = $serializerInterface->serialize($book, 'json', ['groups' => 'getBooks']);
        return new JsonResponse($jsonBook, Response::HTTP_OK, [], true);
    }

    #[Route('/api/books/{id}', name: 'delete_book', methods: ['DELETE'])]
    public function deleteBook(Book $book, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($book);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

}
