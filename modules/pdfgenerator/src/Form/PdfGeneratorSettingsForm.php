<?php

namespace Drupal\pdfgenerator\Form;

// Permet d'implémenter l'interface de configuration de Drupal.
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

        /*
        $form['setting'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Setting'),
            '#default_value' => $config->get('setting'),
        ];
        */

        $form['show_button_on_articles'] = [
            '#type' => 'checkbox',
            '#title' => $this->t('[A] Activer ou désactiver le bouton de la génération de PDF pour les articles publiés'),
            '#default_value' => $config->get('show_button_on_articles'),
            '#description' => $this->t('Si cette case est cochée, le bouton de la génération de PDF sera affiché pour les articles publiés.'),
        ];

        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $config = $this->config('pdfgenerator.settings');
        $config->set('show_button_on_articles', $form_state->getValue('show_button_on_articles'));
        $config->save();

        parent::submitForm($form, $form_state);
    }
}
