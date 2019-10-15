<?php

namespace Drupal\schema_medical_business\Plugin\metatag\Tag;

use Drupal\schema_metatag\Plugin\metatag\Tag\SchemaImageBase;

/**
 * Provides a plugin for the 'image' meta tag.
 *
 * - 'id' should be a globally unique id.
 * - 'name' should match the Schema.org element name.
 * - 'group' should match the id of the group that defines the Schema.org type.
 *
 * @MetatagTag(
 *   id = "schema_medical_business_image",
 *   label = @Translation("image"),
 *   description = @Translation("The primary image for this item."),
 *   name = "image",
 *   group = "schema_medical_business",
 *   weight = 3,
 *   type = "image",
 *   secure = FALSE,
 *   multiple = FALSE
 * )
 */
class SchemaMedicalBusinessImage extends SchemaImageBase {
  // Nothing here yet. Just a placeholder class for a plugin.
}
