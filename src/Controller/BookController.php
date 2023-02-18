<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Book;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class BookController
 *@author  Tresor-ilunga <19im065@esisalama.org>
 */
class BookController extends AbstractController
{
    /**
     * This method return all books
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
     * This method return a detail of a book
     * @param Book $book
     * @param SerializerInterface $serializerInterface
     * @return JsonResponse
     */
    #[Route('/api/books/{id}', name: 'book_detail', methods: ['GET'])]
    public function getDetailBook(Book $book, SerializerInterface $serializerInterface): JsonResponse
    {
        $jsonBook = $serializerInterface->serialize($book, 'json', ['groups' => 'getBooks']);
        return new JsonResponse($jsonBook, Response::HTTP_OK, [], true);
    }

    /**
     * This method delete a book
     * @param Book $book
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    #[Route('/api/books/{id}', name: 'delete_book', methods: ['DELETE'])]
    public function deleteBook(Book $book, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($book);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * This method create a book
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $em
     * @param UrlGeneratorInterface $urlGenerator
     * @return JsonResponse
     */
    #[Route('/api/books', name: 'create_book', methods: ['POST'])]
    public function createBook(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
        $book = $serializer->deserialize($request->getContent(), Book::class, 'json');
        $em->persist($book);
        $em->flush();

        $jsonBook = $serializer->serialize($book, 'json', ['groups' => 'getBooks']);
        $location = $urlGenerator->generate('book_detail', ['id' => $book->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonBook, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    /**
     * This method update a book
     * @param Request $request
     * @param Book $currentBook
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $em
     * @param AuthorRepository $authorRepository
     * @return JsonResponse
     */
    #[Route('/api/books/{id}', name: 'update_book', methods: ['PUT'])]
    public function updateBook(Request $request,Book $currentBook , SerializerInterface $serializer, EntityManagerInterface $em, AuthorRepository $authorRepository): JsonResponse
    {
        $updateBook = $serializer->deserialize($request->getContent(), Book::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $currentBook]);

        $content = $request->toArray();
        $idAuthor = $content["idAuthor"] ?? -1;

        $updateBook->setAuthor($authorRepository->find($idAuthor));

        $em->persist($updateBook);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

}
