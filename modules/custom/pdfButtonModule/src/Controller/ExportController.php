<?php
namespace Drupal\pdfButtonModule\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Drupal\media\Entity\Media;
use Drupal\node\Entity\Node;
use Drupal\image\Entity\ImageStyle;
use Drupal\file\Entity\File;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Render\RendererInterface;
use TCPDF;

class ExportController extends ControllerBase {

  public function description(): array {
    return [
      '#type' => 'markup',
      '#markup' => t('Hello world'),
    ];
  }

//  public function exportPdf($node): Response {
//    // Vérifiez si le nœud existe et a le type de contenu requis.
//    $node = \Drupal::entityTypeManager()->getStorage('node')->load($node);
//    if (!$node || $node->getType() !== 'article' || $node->getType() !== 'article_image') {
//      throw new NotFoundHttpException();
//    }
//
//    // Générez le contenu PDF.
//    $pdfContent = $this->generatePdfContent($node);
//
//    // Créez une réponse avec le contenu PDF.
//    $response = new Response($pdfContent);
//
//    // Configurez les en-têtes appropriés pour le PDF.
//    $response->headers->set('Content-Type', 'application/pdf');
//    $response->headers->set('Content-Disposition', 'attachment; filename="article.pdf"');
//
//    // Retournez la réponse.
//    return $response;
//  }
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
//    $pdfContent = $this->generatePdfContent($node);

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
//  private function generatePdfContent(EntityInterface $entity): string {
//    $image = '';
//    $article = '';
//
//    if ($entity->getEntityTypeId() === 'media' && $entity->bundle() === 'article_image') {
//      $articleImage = $entity;
//      if ($articleImage->hasField('field_image') && !$articleImage->get('field_image')->isEmpty()) {
//        $imageField = $articleImage->get('field_image')->first();
//        $file = File::load($imageField->target_id);
//        if ($file && $file->isPermanent()) {
//          $imageStyle = ImageStyle::load('your_image_style'); // Replace 'your_image_style' with the actual image style machine name.
//          $imageUrl = $imageStyle->buildUrl($file->getFileUri());
//          $image = '<img src="' . $imageUrl . '">';
//        }
//      }
//    }
//
//    if ($entity->getEntityTypeId() === 'node' && $entity->bundle() === 'article') {
//      $articleEntity = $entity;
//      if ($articleEntity->hasField('field_content')) {
//        $contentField = $articleEntity->get('field_content');
//        $contentRenderArray = $this->renderer->render($contentField);
//        $article = $this->renderer->renderRoot($contentRenderArray);
//      }
//    }
//
//    // Generate the PDF content using TCPDF.
//    $pdf = new TCPDF();
//    $pdf->AddPage();
//    $pdf->SetFont('helvetica', '', 12);
//    $pdf->Write(0, $image, '', 0, 'L', true, 0, false, false, 0);
//    $pdf->Write(0, $article, '', 0, 'L', true, 0, false, false, 0);
//    $pdfContent = $pdf->Output('article.pdf', 'S');
//
//    // Return the PDF content as a string.
//    return $pdfContent;
//  }

  private function generatePdf(array $article): string
  {

    // Generate the PDF content using TCPDF.
    $pdf = new TCPDF();
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Write(0, $article['title'], '', 0, 'L', true, 0, false, false, 0);
    $pdf->Ln(10); // Add some space between title and content
    $pdf->Write(0, $article['content'], '', 0, 'L', true, 0, false, false, 0);
    $pdfContent = $pdf->Output('article.pdf', 'S');

    // Return the PDF content as a string.
    return $pdfContent;
  }

/** test working */
//  private function generatePdfContent($entity): string
//  {
//    // Generate the PDF content using TCPDF.
//    $pdf = new TCPDF();
//    $pdf->AddPage();
//    $pdf->SetFont('helvetica', '', 12);
//    $pdf->Write(0, 'Hello PDF!', '', 0, 'L', true, 0, false, false, 0);
//    $pdfContent = $pdf->Output('article.pdf', 'S');
//
//    // Return the PDF content as a string.
//    return $pdfContent;
//  }
}
