<?php

namespace Drupal\towerhealth_rating_migration\Plugin\migrate\process;

use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;
use Drupal\paragraphs\Entity\Paragraph;
use DateTime;

/**
 * Creates rating comment paragraphs.
 *
 * @see \Drupal\migrate\Plugin\MigrateProcessInterface
 *
 * @MigrateProcessPlugin(
 *   id = "towerhealth_rating_comments",
 *   handle_multiples = TRUE
 * )
 */
class TowerHealthRatingComments extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    $comments = $value;
    $paragraphs = [];

    if (empty($comments)) {
      return $paragraphs;
    }

    foreach ($comments as $comment_value) {
      $comment = [];

      $comment['type'] = 'rating_comments';
      $comment['bundle'] = 'rating_comments';
      $comment['field_rating_comment_id'] = $comment_value['id'];
      if (isset($comment_value['overallRating']['value'])) {
        $comment['field_rating_comment_rating'] = $comment_value['overallRating']['value'];
      }
      $date = new DateTime($comment_value['mentionTime']);
      $comment['field_rating_comment_date'] = $date->format('F j, Y');
      $comment['field_rating_comment_body'] = $comment_value['comment'];

      $comment_paragraph = Paragraph::create($comment);
      $comment_paragraph->save();

      $target_id_dest = $comment_paragraph->id();
      $target_revision_id_dest = $comment_paragraph->getRevisionId();

      $paragraphs[] = [
        'target_id' => $target_id_dest,
        'target_revision_id' => $target_revision_id_dest,
      ];
    }

    return $paragraphs;
  }

}
