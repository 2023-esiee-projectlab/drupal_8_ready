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
class PdfButtonBlock extends BlockBase {
  public function build(): array {
    return array (
      '#markup' => $this->t('Hello Mec'.$this->configuration['block_firstname']),
    );
  }

  public function blockForm($form, FormStateInterface $form_state) {
    $form['block_configuration'] = [
      '#type'=>'textfield',
      '#title'=>$this->t('FirstName'),
      '#description' => $this->t('Enter your first Name'),
      '#default_value' => $this->configuration['block_firstname'],
    ];

    return $form;
  }

  public function defaultConfiguration(): array {
    return [
      'block_firstname' => 'word',
    ];
  }

  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['block_firstname'] = $form_state->getValue('block_configuration');
  }

}
