<?php
namespace Drupal\my_pdf_module\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use TCPDF;

class ExportController extends ControllerBase {

  public function exportPdf(): Response {
    $node = $this->getCurrentNode();

    if (!$node) {
      throw new NotFoundHttpException();
    }

    // Generate the PDF content.
    $pdfContent = $this->generatePdfContent($node);

    // Create a response with the PDF content.
    $response = new Response($pdfContent);

    // Set the appropriate headers for PDF.
    $response->headers->set('Content-Type', 'application/pdf');
    $response->headers->set('Content-Disposition', 'attachment; filename="article.pdf"');

    // Return the response.
    return $response;
  }

  /**
   * Get the current node object.
   *
   * @return \Drupal\node\Entity\Node|null
   *   The current node object or NULL if not found.
   */
  private function getCurrentNode(): ?Node {
    $node = \Drupal::routeMatch()->getParameter('node');

    if ($node instanceof Node) {
      return $node;
    }

    return NULL;
  }

  /**
   * Generate the PDF content from the article node.
   */
  private function generatePdfContent(Node $node): string {
    // Get the rendered HTML of the article content.
    $content = \Drupal::service('renderer')->renderRoot($node->body->view('full'));

    // Implement your logic to generate the PDF content using the $content variable.
    // You can use libraries like TCPDF, Dompdf, or any other PDF generation library.
    // For example, with TCPDF:

    $pdf = new TCPDF();

    // Set PDF content and formatting.
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Write(0, $content, '', 0, 'L', true, 0, false, false, 0);

    // Return the PDF content as a string.
    return $pdf->Output('', 'S');
  }

}
