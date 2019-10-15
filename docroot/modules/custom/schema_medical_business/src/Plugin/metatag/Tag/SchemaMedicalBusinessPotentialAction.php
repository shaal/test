<?php

namespace Drupal\schema_medical_business\Plugin\metatag\Tag;

use Drupal\schema_metatag\Plugin\metatag\Tag\SchemaActionBase;
use Drupal\schema_metatag\Plugin\metatag\Tag\SchemaEntryPointBase;
use Drupal\schema_metatag\Plugin\metatag\Tag\SchemaThingBase;

/**
 * Provides a 'schema_medical_business_potential_action' meta tag.
 *
 * - 'id' should be a globally unique id.
 * - 'name' should match the Schema.org element name.
 * - 'group' should match the id of the group that defines the Schema.org type.
 *
 * @MetatagTag(
 *   id = "schema_medical_business_potential_action",
 *   label = @Translation("potentialAction"),
 *   description = @Translation("RECOMMENDED BY GOOGLE. Potential action provided by this medical_business."),
 *   name = "potentialAction",
 *   group = "schema_medical_business",
 *   weight = 15,
 *   type = "string",
 *   secure = FALSE,
 *   multiple = TRUE
 * )
 */
class SchemaMedicalBusinessPotentialAction extends SchemaActionBase {

  /**
   * Generate a form element for this meta tag.
   */
  public function form(array $element = []) {

    $this->actionTypes = ['TradeAction', 'OrganizeAction'];
    $this->actions = ['OrderAction', 'ReserveAction'];

    $form = parent::form($element);
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public static function testValue() {
    $items = [];
    $keys = self::actionFormKeys('OrganizeAction');
    foreach ($keys as $key) {
      switch ($key) {

        case '@type':
          $items[$key] = 'ReserveAction';
          break;

        case 'target':
          $items[$key] = SchemaEntryPointBase::testValue();
          break;

        case 'result':
          $items[$key] = SchemaThingBase::testValue();
          break;

        default:
          $items[$key] = parent::testDefaultValue(1, '');
          break;

      }
    }
    return $items;

  }

}
