<?php

namespace Drupal\pdf_exporter\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;

class PdfExportController extends ControllerBase {

  /**
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function exportPdf($node): Response {
    // Load the node object based on the provided node ID.
    $node = \Drupal::entityTypeManager()->getStorage('node')->load($node);

    // Perform any necessary processing to generate the PDF content.

    // Generate the PDF file using a library or service of your choice.
    // Here, we assume the existence of a custom PDF generation service.
    $pdfData = \Drupal::service('pdf_generator')->generatePdf($node);

    // Send the PDF file as a response.
    $response = new Response($pdfData);
    $response->headers->set('Content-Type', 'application/pdf');
    $response->headers->set('Content-Disposition', 'attachment; filename="exported_article.pdf"');

    return $response;
  }

}
