<?php

namespace Drupal\schema_medical_business\Plugin\metatag\Tag;

use Drupal\schema_metatag\Plugin\metatag\Tag\SchemaAddressBase;

/**
 * Provides a plugin for the 'schema_medical_business_address' meta tag.
 *
 * - 'id' should be a globally unique id.
 * - 'name' should match the Schema.org element name.
 * - 'group' should match the id of the group that defines the Schema.org type.
 *
 * @MetatagTag(
 *   id = "schema_medical_business_address",
 *   label = @Translation("address"),
 *   description = @Translation("REQUIRED BY GOOGLE. The address of the medical business."),
 *   name = "address",
 *   group = "schema_medical_business",
 *   weight = 10,
 *   type = "string",
 *   secure = FALSE,
 *   multiple = FALSE
 * )
 */
class SchemaMedicalBusinessAddress extends SchemaAddressBase {

}
