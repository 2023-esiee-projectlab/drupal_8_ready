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

        // Block général
        $form['general'] = [ '#type' => 'details', '#title' => t('Général'), '#open' => TRUE, ];

        // Sous-block de Police
        $form['general']['show_button_on_articles'] = [
            '#type' => 'checkbox',
            '#title' => $this->t('Activer ou désactiver le bouton de la génération de PDF pour les articles publiés'),
            '#default_value' => $config->get('show_button_on_articles'),
            '#description' => $this->t('Si cette case est cochée, le bouton de la génération de PDF sera affiché pour les articles publiés.'),
        ];

        // Block de Mise en page
        $form['mise_en_page'] = [ '#type' => 'details', '#title' => t('Mise en page'), '#open' => TRUE, ];

        // Sous-block de Police
        $form['mise_en_page']['police'] = [ '#type' => 'details', '#title' => t('Police'), '#open' => TRUE, ];

        // Options de Police
        $form['mise_en_page']['police']['police_select'] = [ '#type' => 'select', '#title' => $this->t('Police'),
            '#options' => [
                'option1' => $this->t('Option 1'),
                'option2' => $this->t('Option 2'),
                'option3' => $this->t('Option 3'),
            ], '#default_value' => $config->get('police_select')
        ];
        $form['mise_en_page']['police']['police_size'] = [ '#type' => 'select', '#title' => $this->t('Taille de la police'),
            '#options' => [
                '8' => $this->t('8'), '9' => $this->t('9'), '10' => $this->t('10'), '11' => $this->t('11'),
                '12' => $this->t('12'), '14' => $this->t('14'), '16' => $this->t('16'), '18' => $this->t('18'),
                '20' => $this->t('20')
            ],
            '#default_value' => $config->get('police_size')
        ];

        // Sous-block de Marges
        $form['mise_en_page']['marges'] = [ '#type' => 'details', '#title' => t('Marges'), '#open' => TRUE, ];
        $form['mise_en_page']['marges']['marges_top'] = ['#type' => 'select', '#title' => $this->t('Marge - Haut'),
            '#options' => [
                '1,0' => $this->t('1,0'), '1,5' => $this->t('1,5'), '2,0' => $this->t('2,0'), '2,5' => $this->t('2,5')
            ], '#default_value' => $config->get('marges_top')
        ];
        $form['mise_en_page']['marges']['marges_bottom'] = [ '#type' => 'select', '#title' => $this->t('Marge - Bas'),
            '#options' => [
                '1,0' => $this->t('1,0'), '1,5' => $this->t('1,5'), '2,0' => $this->t('2,0'), '2,5' => $this->t('2,5')
            ], '#default_value' => $config->get('marges_bottom')
        ];
        $form['mise_en_page']['marges']['marges_left'] = [ '#type' => 'select', '#title' => $this->t('Marge - Gauche'),
            '#options' => [
                '1,0' => $this->t('1,0'), '1,5' => $this->t('1,5'), '2,0' => $this->t('2,0'), '2,5' => $this->t('2,5')
            ], '#default_value' => $config->get('marges_left')
        ];
        $form['mise_en_page']['marges']['marges_right'] = [ '#type' => 'select', '#title' => $this->t('Marge - Droite'),
            '#options' => [
                '1,0' => $this->t('1,0'), '1,5' => $this->t('1,5'), '2,0' => $this->t('2,0'), '2,5' => $this->t('2,5')
            ], '#default_value' => $config->get('marges_right')
        ];
        $form['mise_en_page']['marges']['marges_reliure'] = [ '#type' => 'select', '#title' => $this->t('Marge - Reliure'),
            '#options' => [
                '0,0' => $this->t('0,0'), '0,5' => $this->t('0,5'),
                '1,0' => $this->t('1,0'), '1,5' => $this->t('1,5'),
                '2,0' => $this->t('2,0'), '2,5' => $this->t('2,5')
            ], '#default_value' => $config->get('marges_reliure')
        ];
        $form['mise_en_page']['marges']['marges_reliure_position'] = [ '#type' => 'select', '#title' => $this->t('Marge - Position de la reliure'),
            '#options' => [
                'gauche' => $this->t('Gauche'),
                'droite' => $this->t('Droite')
            ], '#default_value' => $config->get('marges_reliure')
        ];

        // Sous-block de Orientation
        $form['mise_en_page']['orientation'] = [ '#type' => 'details', '#title' => t('Orientation'), '#open' => TRUE, ];
        $form['mise_en_page']['orientation']['orientation_portrait'] = [ '#type' => 'button', '#value' => $this->t('Portrait') ];
        $form['mise_en_page']['orientation']['orientation_paysage'] = [ '#type' => 'button', '#value' => $this->t('Paysage') ];

        // Sous-block de Taille de la page
        $form['mise_en_page']['taille_page'] = [ '#type' => 'details', '#title' => t('Taille de la page'), '#open' => TRUE, ];
        $form['mise_en_page']['taille_page']['taille_page_format'] = [ '#type' => 'select', '#title' => $this->t('Format de la page'),
            '#options' => [
                'A3' => $this->t('A3 - 29,7 x 42 cm'),
                'A4' => $this->t('A4 - 20,98 x 29,7 cm'),
                'A5' => $this->t('A5 - 14,8 x 21 cm')
            ], '#default_value' => $config->get('marges_reliure')
        ];

        // Sous-block de Ensemble de pages
        $form['mise_en_page']['ensemble_pages'] = [ '#type' => 'details', '#title' => t('[Features-Bonus] Ensemble de pages'), '#open' => TRUE, ];
        $form['mise_en_page']['ensemble_pages']['ensemble_pages_page_de_garde'] = [
            '#type' => 'checkbox',
            '#title' => $this->t('Page de garde'),
            '#default_value' => $config->get('show_button_on_articles'),
            '#description' => $this->t('Si cette case est cochée, une page de garde sera ajoutée au début du document.'),
        ];
        $form['mise_en_page']['ensemble_pages']['ensemble_pages_page_de_separation'] = [
            '#type' => 'checkbox',
            '#title' => $this->t('Page de séparation'),
            '#default_value' => $config->get('show_button_on_articles'),
            '#description' => $this->t('Si cette case est cochée, une page vierge de séparation sera ajoutée après la page de garde.'),
        ];
        $form['mise_en_page']['ensemble_pages']['ensemble_pages_haut_de_page'] = [
            '#type' => 'checkbox',
            '#title' => $this->t('Haut de page'),
            '#default_value' => $config->get('show_button_on_articles'),
            '#description' => $this->t('Si cette case est cochée, le haut de page sera ajouté à chaque page du document.'),
        ];
        $form['mise_en_page']['ensemble_pages']['ensemble_pages_bas_de_page'] = [
            '#type' => 'checkbox',
            '#title' => $this->t('Bas de page'),
            '#default_value' => $config->get('show_button_on_articles'),
            '#description' => $this->t('Si cette case est cochée, le bas de page sera ajouté à chaque page du document.'),
        ];

        // Sous-block de Pages de converture
        $form['mise_en_page']['ensemble_pages']['pages_converture'] = [ '#type' => 'details', '#title' => t('[Features-Bonus] Pages de converture'), '#open' => TRUE, ];
        $form['mise_en_page']['ensemble_pages']['pages_converture']['pages_converture_first'] = [
            '#type' => 'checkbox',
            '#title' => $this->t('Première page de couverture'),
            '#default_value' => $config->get('pages_converture_first'),
            '#description' => $this->t('Si cette case est cochée, une page recto de couverture sera ajoutée au début du document.'),
        ];
        $form['mise_en_page']['ensemble_pages']['pages_converture']['pages_converture_second'] = [
            '#type' => 'checkbox',
            '#title' => $this->t('Deuxième page de couverture'),
            '#default_value' => $config->get('pages_converture_second'),
            '#description' => $this->t('Si cette case est cochée, une page verso de couverture sera ajoutée au début du document (inclus la première page de couverture).'),
        ];
        $form['mise_en_page']['ensemble_pages']['pages_converture']['pages_converture_third'] = [
            '#type' => 'checkbox',
            '#title' => $this->t('troisième page de couverture'),
            '#default_value' => $config->get('pages_converture_third'),
            '#description' => $this->t('Si cette case est cochée, une page recto de couverture sera ajoutée à la fin du document.'),
        ];
        $form['mise_en_page']['ensemble_pages']['pages_converture']['pages_converture_four'] = [
            '#type' => 'checkbox',
            '#title' => $this->t('Quatrième page de couverture'),
            '#default_value' => $config->get('pages_converture_four'),
            '#description' => $this->t('Si cette case est cochée, une page verso de couverture sera ajoutée à la fin du document (inclus la troisième page de couverture).'),
        ];

        /* Champs de text de démonstration
        $form['setting'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Setting'),
            '#default_value' => $config->get('setting'),
        ];
        */

        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $config = $this->config('pdfgenerator.settings');
        /*
        $config->set('setting', $form_state->getValue('setting'));
        $config->set('show_button_on_articles', $form_state->getValue('show_button_on_articles'));
        $config->set('test_select', $form_state->getValue('test_select'));
        $config->set('test_combobox', $form_state->getValue('test_combobox'));
        */
        $config->save();

        parent::submitForm($form, $form_state);
    }
}
