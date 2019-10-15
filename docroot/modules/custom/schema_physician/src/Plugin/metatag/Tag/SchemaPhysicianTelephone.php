<?php

namespace Drupal\schema_physician\Plugin\metatag\Tag;

use Drupal\schema_metatag\Plugin\metatag\Tag\SchemaNameBase;

/**
 * Provides a plugin for the 'schema_physician_telephone' meta tag.
 *
 * - 'id' should be a globally unique id.
 * - 'name' should match the Schema.org element name.
 * - 'group' should match the id of the group that defines the Schema.org type.
 *
 * @MetatagTag(
 *   id = "schema_physician_telephone",
 *   label = @Translation("telephone"),
 *   description = @Translation("The telephone number of the physician."),
 *   name = "telephone",
 *   group = "schema_physician",
 *   weight = 0,
 *   type = "string",
 *   secure = FALSE,
 *   multiple = TRUE
 * )
 */
class SchemaPhysicianTelephone extends SchemaNameBase {
  // Nothing here yet. Just a placeholder class for a plugin.
}
