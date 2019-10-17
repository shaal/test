<?php

namespace Drupal\schema_physician\Plugin\metatag\Tag;

use Drupal\schema_metatag\Plugin\metatag\Tag\SchemaImageBase;

/**
 * Provides a plugin for the 'schema_physician_image' meta tag.
 *
 * - 'id' should be a globally unique id.
 * - 'name' should match the Schema.org element name.
 * - 'group' should match the id of the group that defines the Schema.org type.
 *
 * @MetatagTag(
 *   id = "schema_physician_image",
 *   label = @Translation("image"),
 *   description = @Translation("The primary image for the physician."),
 *   name = "image",
 *   group = "schema_physician",
 *   weight = 1,
 *   type = "image",
 *   secure = FALSE,
 *   multiple = FALSE
 * )
 */
class SchemaPhysicianImage extends SchemaImageBase {

  /**
   * Generate a form element for this meta tag.
   */
  public function form(array $element = []) {
    $form = parent::form($element);
    $form['#attributes']['placeholder'] = '[node:field_image:url]';
    return $form;
  }

}
