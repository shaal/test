<?php

namespace Drupal\schema_physician\Plugin\metatag\Tag;

use Drupal\schema_metatag\Plugin\metatag\Tag\SchemaAddressBase;

/**
 * Provides a plugin for the 'schema_physician_address' meta tag.
 *
 * - 'id' should be a globally unique id.
 * - 'name' should match the Schema.org element name.
 * - 'group' should match the id of the group that defines the Schema.org type.
 *
 * @MetatagTag(
 *   id = "schema_physician_address",
 *   label = @Translation("address"),
 *   description = @Translation("Physical address of the physician."),
 *   name = "address",
 *   group = "schema_physician",
 *   weight = 1,
 *   type = "string",
 *   secure = FALSE,
 *   multiple = FALSE
 * )
 */
class SchemaPhysicianAddress extends SchemaAddressBase {
  // Nothing here yet. Just a placeholder class for a plugin.
}
