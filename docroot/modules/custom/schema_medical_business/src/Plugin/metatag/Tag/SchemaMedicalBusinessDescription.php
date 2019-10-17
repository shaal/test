<?php

namespace Drupal\schema_medical_business\Plugin\metatag\Tag;

use Drupal\schema_metatag\Plugin\metatag\Tag\SchemaNameBase;

/**
 * Provides a plugin for the 'schema_medical_business_description' meta tag.
 *
 * - 'id' should be a globally unique id.
 * - 'name' should match the Schema.org element name.
 * - 'group' should match the id of the group that defines the Schema.org type.
 *
 * @MetatagTag(
 *   id = "schema_medical_business_description",
 *   label = @Translation("description"),
 *   description = @Translation("RECOMMENDED BY GOOGLE. A description of the medical business."),
 *   name = "description",
 *   group = "schema_medical_business",
 *   weight = 1,
 *   type = "label",
 *   secure = FALSE,
 *   multiple = FALSE
 * )
 */
class SchemaMedicalBusinessDescription extends SchemaNameBase {

  /**
   * Generate a form element for this meta tag.
   */
  public function form(array $element = []) {
    $form = [
      '#type' => 'textarea',
      '#title' => $this->label(),
      '#default_value' => $this->value(),
      '#row' => 2,
      '#required' => isset($element['#required']) ? $element['#required'] : FALSE,
      '#description' => $this->description(),
      '#element_validate' => [[get_class($this), 'validateTag']],
    ];
    return $form;
  }

}
