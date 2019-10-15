<?php

namespace Drupal\schema_physician\Plugin\metatag\Tag;

use Drupal\schema_metatag\Plugin\metatag\Tag\SchemaPersonOrgBase;

/**
 * Provides a plugin for the 'schema_physician_employee' meta tag.
 *
 * - 'id' should be a globally unique id.
 * - 'name' should match the Schema.org element name.
 * - 'group' should match the id of the group that defines the Schema.org type.
 *
 * @MetatagTag(
 *   id = "schema_physician_employee",
 *   label = @Translation("employee"),
 *   description = @Translation("The physician."),
 *   name = "employee",
 *   group = "schema_physician",
 *   weight = 1,
 *   type = "string",
 *   secure = FALSE,
 *   multiple = FALSE
 * )
 */
class SchemaPhysicianEmployee extends SchemaPersonOrgBase {
  // Nothing here yet. Just a placeholder class for a plugin.
}
