<?php

namespace Drupal\schema_physician\Plugin\metatag\Tag;

use Drupal\schema_metatag\Plugin\metatag\Tag\SchemaNameBase;

/**
 * Provides a plugin for the 'schema_physician_medical_specialty' meta tag.
 *
 * - 'id' should be a globally unique id.
 * - 'name' should match the Schema.org element name.
 * - 'group' should match the id of the group that defines the Schema.org type.
 *
 * @MetatagTag(
 *   id = "schema_physician_medical_specialty",
 *   label = @Translation("medicalSpecialty"),
 *   description = @Translation("The medical specialty of the physician."),
 *   name = "medicalSpecialty",
 *   group = "schema_physician",
 *   weight = 0,
 *   type = "string",
 *   secure = FALSE,
 *   multiple = FALSE
 * )
 */
class SchemaPhysicianMedicalSpecialty extends SchemaNameBase {
  // Nothing here yet. Just a placeholder class for a plugin.
}
