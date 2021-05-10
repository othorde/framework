<?php

namespace App\Controller;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class BookController extends AbstractController
{


    /**
    * @Route("/create", name="create_book")
    */
    public function createBook(ValidatorInterface $validator): Response
    {
        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to the action: createBook(EntityManagerInterface $entityManager)
        $entityManager = $this->getDoctrine()->getManager();

        $Book = new Book();
        $Book->setTitle('Sagan om ringen');
        $Book->setISBN("388053101-1");
        $Book->setAuthor('J R R Tolkien');
        $Book->setFrontcover("https://unsplash.com/photos/riw_BZvlMcI");

        // tell Doctrine you want to (eventually) save the Book (no queries yet)
        $entityManager->persist($Book);
        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        $Book = new Book();
        $Book->setTitle('Sagan om de två tornen');
        $Book->setISBN("388053101-2");
        $Book->setAuthor('J R R Tolkien');
        $Book->setFrontcover("https://unsplash.com/photos/608SA-ldxfE");

        // tell Doctrine you want to (eventually) save the Book (no queries yet)
        $entityManager->persist($Book);
        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        $Book = new Book();
        $Book->setTitle('sagan om konungens återkomst');
        $Book->setISBN("388053101-3");
        $Book->setAuthor('J R R Tolkien');
        $Book->setFrontcover("https://unsplash.com/photos/9jNcTRncjgM");

        // tell Doctrine you want to (eventually) save the Book (no queries yet)
        $entityManager->persist($Book);
        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        $errors = $validator->validate($Book);
        if (count($errors) > 0) {
            return new Response((string) $errors, 400);
        }

        return new Response('Saved new Book with title '.$Book->getTitle());
    }


    /**
     * @Route("/book", name="book_show")
     */
    public function show(): Response
    {
        $book = $this->getDoctrine()
            ->getRepository(Book::class)
            ->findAll();

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for '
            );
        }
        
        //return new Response('Check out this great book: ');

        // or render a template
        // in the template, print things with {{ book.name }}
        return $this->render('book/book.html.twig', ['book' => $book]);
    }


    
    /**
     * @Route("/delete", name="book_delete")
     */
    public function delete(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $Book = $entityManager->getRepository(Book::class)->findAll();

        for ($x = 0; $x < count($Book); $x++) {
            
            $entityManager->remove($Book[$x]);
            $entityManager->flush();
        }

        return new Response('Removed all books new Book with title ');
    }


}
