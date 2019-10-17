<?php

namespace Drupal\schema_medical_business\Plugin\metatag\Tag;

use Drupal\schema_metatag\Plugin\metatag\Tag\SchemaOpeningHoursSpecificationBase;

/**
 * Meta tag plugin for 'schema_medical_business_opening_hours_specification'.
 *
 * - 'id' should be a globally unique id.
 * - 'name' should match the Schema.org element name.
 * - 'group' should match the id of the group that defines the Schema.org type.
 *
 * @MetatagTag(
 *   id = "schema_medical_business_opening_hours_specification",
 *   label = @Translation("openingHoursSpecification"),
 *   description = @Translation("RECOMMENDED BY GOOGLE. Hours during which the business location is open."),
 *   name = "openingHoursSpecification",
 *   group = "schema_medical_business",
 *   weight = 5,
 *   type = "string",
 *   secure = FALSE,
 *   multiple = TRUE
 * )
 */
class SchemaMedicalBusinessOpeningHoursSpecification extends SchemaOpeningHoursSpecificationBase {

}
