<?php

namespace Drupal\pdfgenerator\Block;

use Drupal\Core\Annotation\Block;
use Drupal\Core\Block\BlockBase;

/**
 * Provides a custom block for PDF Generator configuration.
 *
 * @Block(
 *   id = "pdf_generator_config_block",
 *   admin_label = @Translation("PDF Generator Config Block"),
 *   category = @Translation("PDF Generator")
 * )
 */
@Block;
class PdfGeneratorSettingsBlock extends BlockBase {

    /**
     * {@inheritdoc}
     */
    public function build() {
        return [
            '#markup' => $this->t('This is the PDF Generator configuration block.'),
        ];
    }
}
