<?php

namespace Drupal\pdfButtonModule\Plugin\Block;
use \Drupal\Core\Block\BlockBase;
use \Drupal\Core\Form\FormStateInterface;
/**
 * Provides a PDF Button block.
 *
 * @Block(
 *   id = "block_pdf_button",
 *   admin_label = @Translation("PDF Button"),
 *   category = @Translation("Button"),
 * )
 */
class PdfButtonBlock extends BlockBase{
  public function build() {
    return array (
      '#markup' => $this->t('Hello Mec'.$this->configuration['block_firstname']),
    );
  }

}
