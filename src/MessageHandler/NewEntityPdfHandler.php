<?php

namespace App\MessageHandler;

use App\Entity\Image;
use App\Message\NewEntityPdf;
use App\Repository\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Twig\Environment;
use function PHPUnit\Framework\directoryExists;

#[AsMessageHandler]
final class NewEntityPdfHandler
{
    public function __construct(
        private EntityRepository $entityRepository,
        private Environment $twig,
        private EntityManagerInterface $entityManager,
        private ParameterBagInterface $parameterBag,
    ) {
    }

    public function __invoke(NewEntityPdf $entityPdf)
    {
        // Get entity data
        $entity = $this->entityRepository->find($entityPdf->getEntityId());

        // Instantiate dompdf class
        $dompdf = new Dompdf();

        // Convert images to base64 to fix issue on images not being loaded on PDF
        $images = $entity->getImages();
        $base64Images = [];
        /** @var Image $image */
        foreach ($images as $image) {
            $path = $this->parameterBag->get('uploads_directory') . '/' . $image->getPath();
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64Images[] = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        // Get html content
        $htmlContent = $this->twig->render('pdf/index.html.twig', [
            'entity' => $entity,
            'images' => $base64Images,
        ]);

        // Write html content to pdf
        $dompdf->loadHtml($htmlContent);

        // Render the HTML as PDF
        $dompdf->render();

        // Set paper size
        $dompdf->setPaper('A4');

        // Create pdf download dir if it does not exist
        if (!is_dir($this->parameterBag->get('pdf_directory'))) {
            // Recursive dir creation
            mkdir($this->parameterBag->get('pdf_directory'), 0777, true);
        }

        // Save to PDF file
        $filename = strtolower(str_replace(' ', '-', $entity->getName())) . uniqid() . '.pdf';
        file_put_contents($this->parameterBag->get('pdf_directory') . '/' . $filename, $dompdf->output());

        // Update pdf file path to Entity for the PDF download link reference
        $entity->setPdfFile($filename);
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
}
