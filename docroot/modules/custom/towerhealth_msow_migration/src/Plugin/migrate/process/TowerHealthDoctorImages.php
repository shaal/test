<?php

namespace Drupal\towerhealth_msow_migration\Plugin\migrate\process;

use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;
use Drupal\Core\File\FileSystemInterface;
use Drupal\media\Entity\Media;
use Drupal\taxonomy\Entity\Term;
use Drupal\Core\Language\Language;

/**
 * Creates provider credential paragraphs.
 *
 * @see \Drupal\migrate\Plugin\MigrateProcessInterface
 *
 * @code
 *   field_text:
 *     plugin: towerhealth_doctor_images
 *     name: title to name the file
 *     source: value to lookup
 * @endcode
 *
 * @MigrateProcessPlugin(
 *   id = "towerhealth_doctor_images",
 *   handle_multiples = TRUE
 * )
 */
class TowerHealthDoctorImages extends ProcessPluginBase {

  /**
   * Returns file system service.
   *
   * @return \Drupal\Core\File\FileSystemInterface
   *   The file system service.
  */
  private function getFileSystem() {
    return \Drupal::service('file_system');
  }

  /**
   * @param $file_path
   */
  private function createFile($file_path) {
    $file = '';

    $path = 'public://doctors/';

    $this->getFileSystem()->prepareDirectory($path, FileSystemInterface::CREATE_DIRECTORY);

    if ($file_path) {
      $name = basename($file_path);

      $file_data = file_get_contents($file_path);
      $file = file_save_data($file_data, $path . $name, FileSystemInterface::EXISTS_REPLACE);
    }

    return $file;
  }

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    $id = '';

    if (empty($value) || is_null($value) || $value === 'NULL') {
      return $id;
    }

    $title = '';
    $first_name = $row->getSourceProperty('first_name');
    $last_name = $row->getSourceProperty('last_name');

    if ($first_name && $last_name) {
      $title = $first_name . ' ' . $last_name;
    }

    if (!empty($title)) {
      $file = $this->createFile($value);

      if ($file) {
        $fid = $file->id();

        // Set the image type id.
        $query = \Drupal::entityQuery('taxonomy_term');
        $result = $query
          ->condition('name.0.value', 'Doctor Headshot', '=')
          ->condition('vid', 'image_type')
          ->execute();

        if (reset($result)) {
          $tid = reset($result);
        }
        else {
          $taxonom_term = Term::create([
            'name' => 'Doctor Headshot',
            'vid' => 'image_type',
          ]);
          $taxonom_term->save();
          $tid = $taxonom_term->id();
        }

        $image_media = Media::create([
          'bundle' => 'image',
          'uid' => \Drupal::currentUser()->id(),
          'status' => 1,
          'field_media_image' => [[
            'target_id' => $fid,
            'alt' => t('Image of @name', ['@name' => $title]),
            'title' =>  t('Image of @name', ['@name' => $title]),
          ]],
        ]);
        $image_media->set('field_image_type_ref', $tid);
        $image_media->set('name', $title);
        $image_media->save();

        $id = $image_media->id();
      }
    }

    return ['target_id' => $id];
  }

}
