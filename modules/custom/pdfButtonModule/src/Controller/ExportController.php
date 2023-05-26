<?php
namespace Drupal\pdfButtonModule\Controller;

use Drupal\Core\Controller\ControllerBase;
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

  public function exportPdf($node): Response
  {
    // Vérifiez si le nœud existe et a le type de contenu requis.
    $node = \Drupal::entityTypeManager()->getStorage('node')->load($node);
    if (!$node || ($node->getType() !== 'article' && $node->getType() !== 'article_image')) {
      throw new NotFoundHttpException();
    }
    // Générez le contenu PDF.
    $article = $this->getArticleContent($node);
    $pdfContent = $this->generatePdf($article);

    // Créez une réponse avec le contenu PDF.
    $response = new Response($pdfContent);

    // Configurez les en-têtes appropriés pour le PDF.
    $response->headers->set('Content-Type', 'application/pdf');
    $response->headers->set('Content-Disposition', 'attachment; filename="article.pdf"');

    // Retournez la réponse.
    return $response;
  }

  public function getArticleContent($entity): array
  {
    // get content
    $title = $entity->getTitle();
    $content = $entity->get('body')->value;

    // Replace &nbsp; entities with spaces.
    $content = str_replace('&nbsp;', ' ', $content);
    // Remove HTML tags from the content.
    $content = strip_tags($content);
    return [
      'title'=>$title,
      'content' => $content,
    ];
  }

  private function generatePdf(array $article): string
  {
    // Generate the PDF content using TCPDF.
    $pdf = new TCPDF();
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Write(0, $article['title'], '', 0, 'L', true, 0, false, false, 0);
    $pdf->Ln(10); // Add some space between title and content
    $pdf->Write(0, $article['content'], '', 0, 'L', true, 0, false, false, 0);

    // Return the PDF content as a string.
    return $pdf->Output('article.pdf', 'S');
  }
}
