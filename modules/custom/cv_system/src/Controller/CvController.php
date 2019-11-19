<?php
/**
 * @file
 * Contains \Drupal\first_module\Controller\FirstController.
 */

namespace Drupal\cv_system\Controller;

use Drupal\Core\Controller\ControllerBase;

class CvController extends ControllerBase {

  public function cv_results() {
    return array(
      '#type' => 'markup',
      '#markup' => t('CV info'),
    );
  }

  public function cv_thank_you() {
    return array(
      '#type' => 'markup',
      '#markup' => t('Thank you for submitting your CV. We will call you!'),
    );
  }
}
