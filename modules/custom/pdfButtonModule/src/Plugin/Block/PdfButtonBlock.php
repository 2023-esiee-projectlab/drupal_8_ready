<?php

namespace Drupal\pdfButtonModule\Plugin\Block;
use \Drupal\Core\Block\BlockBase;

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
  public function build(): array
  {
    return array (
      '#markup' => $this->t('Hello Mec'.$this->configuration['block_firstname']),
    );
  }

}
