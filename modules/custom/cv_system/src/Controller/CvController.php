<?php
/**
 * @file
 * Contains \Drupal\first_module\Controller\FirstController.
 */

namespace Drupal\cv_system\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;

class CvController extends ControllerBase {

  public function cv_results() {
    $db = \Drupal::database();
    // Get total rows count.
    $count = $db->select('cv_system', 'cv')
      ->fields('cv')
      ->countQuery()
      ->execute()->fetchField();
    $num_per_page = \Drupal::state()->get('cv_module_num_per_page', 5);

    // Now that we have the total number of results, initialize the pager.
    $page = pager_default_initialize($count, $num_per_page);

    $query = $db->select('cv_system', 'cv');
    $query->fields('cv', []);
    $query->range($page * $num_per_page, $num_per_page);
    $result = $query->execute()->fetchAll();

    $header = [
      'Name',
      'Surname',
      'Email',
      'CV File',
    ];
    foreach ($result as $row) {
      $cv_link = '';
      // Check file exist.
      if ($file = \Drupal\file\Entity\File::load($row->cv_fid)) {
        // Prepare link for file download.
        $file_uri = $file->getFileUri();
        $url = Url::fromUri(file_create_url($file_uri));
        $link = \Drupal\Core\Link::fromTextAndUrl(t('Download CV'), $url);
        $cv_link = $link->toString();
      }
      $rows[] = [
        'data' => [
          $row->name,
          $row->surname,
          $row->email,
          $cv_link,
        ],
      ];
    }

    // Build the table.
    $build = [
      'table'           => [
        '#theme'         => 'table',
        '#attributes'    => [
          'data-striping' => 0
        ],
        '#header' => $header,
        '#rows'   => $rows,
      ],
      'pager' => ['#type' => 'pager'],
    ];

    return $build;
  }

  public function cv_thank_you() {
    return array(
      '#type' => 'markup',
      '#markup' => t('Thank you for submitting your CV. We will call you!'),
    );
  }
}
