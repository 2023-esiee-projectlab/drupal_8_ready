<?php
namespace Drupal\pdfButtonModule\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use TCPDF;

class ExportController extends ControllerBase {
  public function description(): array {
    return [
      '#type' => 'markup',
      '#markup' => t('Hello world'),
    ];
  }

  public function exportPdf($node): Response {
    // Vérifiez si le nœud existe et a le type de contenu requis.
    $node = \Drupal::entityTypeManager()->getStorage('node')->load($node);
    if (!$node || $node->getType() !== 'article' || $node->getType() !== 'article_image') {
      throw new NotFoundHttpException();
    }

    // Générez le contenu PDF.
    $pdfContent = $this->generatePdfContent($node);

    // Créez une réponse avec le contenu PDF.
    $response = new Response($pdfContent);

    // Configurez les en-têtes appropriés pour le PDF.
    $response->headers->set('Content-Type', 'application/pdf');
    $response->headers->set('Content-Disposition', 'attachment; filename="article.pdf"');

    // Retournez la réponse.
    return $response;
  }

  private function generatePdfContent($node): string {
    // Implémentez votre logique pour générer le contenu PDF en utilisant le nœud.
    // Vous pouvez utiliser une bibliothèque PDF comme TCPDF, Dompdf, etc.

    // Exemple avec TCPDF :
    $pdf = new TCPDF();
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Write(0, $node->getTitle(), '', 0, 'L', true, 0, false, false, 0);
    // Ajoutez le contenu supplémentaire selon vos besoins.

    // Retournez le contenu PDF en tant que chaîne.
    return $pdf->Output('', 'S');
  }

}
