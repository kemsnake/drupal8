<?php
/**
 * @file
 * Contains Drupal\cv_system\Form\MessagesForm.
 */
namespace Drupal\cv_system\Form;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class CvForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'cv_system.adminsettings',
    ];
  }

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
      '#title' => $this->t('Name'),
      '#description' => $this->t('User first name'),
      '#default_value' => $config->get('name'),
    ];
    $form['surname'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Surname'),
      '#description' => $this->t('User last name'),
      '#default_value' => $config->get('surname'),
    ];
    $form['email'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Email'),
      '#description' => $this->t('User email'),
      '#default_value' => $config->get('email'),
    ];
    $form['cv_file'] = [
      '#type' => 'file',
      '#title' => $this->t('Upload CV'),
      '#description' => $this->t('CV file'),
      '#default_value' => $config->get('cv_file'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('cv_system.adminsettings')
      ->set('name', $form_state->getValue('name'))
      ->set('surname', $form_state->getValue('surname'))
      ->set('email', $form_state->getValue('email'))
      ->set('cv_file', $form_state->getValue('cv_file'))
      ->save();
  }
}