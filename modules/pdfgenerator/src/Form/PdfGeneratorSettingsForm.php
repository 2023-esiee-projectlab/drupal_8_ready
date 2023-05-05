<?php

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class PdfGeneratorSettingsForm extends ConfigFormBase{

    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'pdfgenerator_settings';
    }

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames() {
        return ['pdfgenerator.settings'];
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $config = $this->config('pdfgenerator.settings');
        $form['pdfgenerator_show_button_articles'] = [
            '#type' => 'checkbox',
            '#title' => $this->t('Activer ou désactiver le bouton de la génération de PDF pour les articles publiés'),
            '#default_value' => $config->get('pdfgenerator_show_button_articles'),
            '#description' => $this->t('Si cette case est cochée, le bouton de la génération de PDF sera affiché pour les articles publiés.'),
        ];

        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $config = $this->config('paragraphs.settings');
        $config->set('show_unpublished', $form_state->getValue('show_unpublished'));
        $config->save();

        parent::submitForm($form, $form_state);
    }
}
