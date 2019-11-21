<?php

namespace Drupal\schema_physician\Plugin\metatag\Tag;

use Drupal\schema_metatag\Plugin\metatag\Tag\SchemaReviewBase;

/**
 * Provides a plugin for the 'schema_physician_review' meta tag.
 *
 * - 'id' should be a globally unique id.
 * - 'name' should match the Schema.org element name.
 * - 'group' should match the id of the group that defines the Schema.org type.
 *
 * @MetatagTag(
 *   id = "schema_physician_review",
 *   label = @Translation("review"),
 *   description = @Translation("review of the physician."),
 *   name = "review",
 *   group = "schema_physician",
 *   weight = 0,
 *   type = "string",
 *   secure = FALSE,
 *   multiple = FALSE
 * )
 */
class SchemaPhysicianReview extends SchemaReviewBase {
  // Nothing here yet. Just a placeholder class for a plugin.
}
