<?php
/**
 * @file
 * Add "url" function for Pattern Lab.
 */

$function = new Twig_SimpleFunction('drupal_entity', function ($string) {
  return $string;
});