<?php

namespace Drupal\schema_medical_business\Plugin\metatag\Group;

use Drupal\schema_metatag\Plugin\metatag\Group\SchemaGroupBase;

/**
 * Provides a plugin for the 'medical_business' meta tag group.
 *
 * @MetatagGroup(
 *   id = "schema_medical_business",
 *   label = @Translation("Schema.org: Medical Business"),
 *   description = @Translation("See Schema.org definitions for this Schema type at <a href="":url"">:url</a>. Also see <a href="":url2"">Google's requirements</a>.", arguments = {
 *     ":url" = "https://schema.org/MedicalBusiness",
 *     ":url2" = "https://developers.google.com/search/docs/data-types/local-business",
 *   }),
 *   weight = 10,
 * )
 */
class SchemaMedicalBusiness extends SchemaGroupBase {
  // Nothing here yet. Just a placeholder class for a plugin.
}
