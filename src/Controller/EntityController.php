<?php

namespace App\Controller;

use App\Entity\Entity;
use App\Entity\Image;
use App\Form\EntityFormType;
use App\Message\NewEntityPdf;
use App\Repository\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class EntityController extends AbstractController
{
    public function __construct(
        private EntityRepository $entityRepository,
        private EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/', name: 'entities')]
    public function index(): Response
    {
        return $this->render('entity/index.html.twig', [
            'entities' => $this->entityRepository->findAll(),
        ]);
    }

    #[Route('/entity/create', name: 'create_entity')]
    public function create(Request $request, MessageBusInterface $bus): Response
    {
        $entity = new Entity();
        $form = $this->createForm(EntityFormType::class, $entity);

        $form->handleRequest($request);

        // Handle form submit and check if form data is valid
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Entity $newEntity */
            $newEntity = $form->getData();

            // Get uploaded image(s)
            $images = $form->get('images')->getData();

            /** @var Image $image */
            foreach($images as $image)
            {
                // Create unique image filename
                $filename = md5(uniqid()) . '.' . $image->guessExtension();

                // Save image to uploads dir
                $image->move(
                    $this->getParameter('uploads_directory'), $filename
                );

                // Instantiate a new Image entity
                $newImage = new Image();
                $newImage->setPath($filename);
                $newImage->setEntity($entity);
                $newEntity->addImage($newImage);
            }

            // Save new entity data to db
            $this->entityManager->persist($newEntity);
            $this->entityManager->flush();

            // Generate PDF asynchronously via message queue
            $bus->dispatch(new NewEntityPdf($newEntity->getId()));

            // After saving, redirect user to the entity list page
            return $this->redirectToRoute('entities');
        }

        return $this->render('entity/create.html.twig', [
            'form' => $form,
        ]);
    }
}
