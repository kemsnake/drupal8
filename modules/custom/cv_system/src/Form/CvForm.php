<?php
/**
 * @file
 * Contains Drupal\cv_system\Form\MessagesForm.
 */

namespace Drupal\cv_system\Form;
use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class CvForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'send_cv_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('cv_system.adminsettings');

    $form['name'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('Name'),
      '#description' => $this->t('User first name'),
      '#default_value' => $config->get('name'),
    ];
    $form['surname'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('Surname'),
      '#description' => $this->t('User last name'),
      '#default_value' => $config->get('surname'),
    ];
    $form['email'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('Email'),
      '#description' => $this->t('User email'),
      '#default_value' => $config->get('email'),
    ];
    $form['cv_file'] = array(
      '#type' => 'managed_file',
      '#name' => 'cv_file',
      '#required' => TRUE,
      '#title' => t('Upload CV'),
      '#size' => 20,
      '#description' => t('PDF and MS word format only'),
      '#upload_validators' => ['file_validate_extensions' => ['pdf', 'docx', 'doc']],
      '#upload_location' => 'public://cv_files/',
    );
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Send CV'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Validate name length.
    if (strlen($form_state->getValue('name')) < 3) {
      $form_state->setErrorByName('name', $this->t('The name should contain more then 2 symbol.'));
    }
    // Validate name length.
    if (strlen($form_state->getValue('surname')) < 3) {
      $form_state->setErrorByName('surname', $this->t('The surname should contain more then 2 symbol.'));
    }
    // Validate email.
    if (!\Drupal::service('email.validator')->isValid($form_state->getValue('email'))) {
      $form_state->setErrorByName('email', $this->t('Please provide a valid email'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $form_file = $form_state->getValue('cv_file', 0);
    // Insert data to custom table.
    Database::getConnection()->insert('cv_system')
      ->fields([
        'name' => $form_state->getValue('name'),
        'surname' => $form_state->getValue('surname'),
        'email' => $form_state->getValue('email'),
        'created' => \Drupal::time()->getRequestTime(),
        'cv_fid' => $form_file[0],
      ])
      ->execute();

    // Redirect user to thank you page.
    $form_state->setRedirect('cv_system.cv_thanks');
  }
}