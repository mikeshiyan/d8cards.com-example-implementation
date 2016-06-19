<?php
/**
 * @file
 * Contains Drupal\rating_formatter\Plugin\Field\FieldFormatter\RatingStars.
 */

namespace Drupal\rating_formatter\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'rating_stars' formatter.
 *
 * @FieldFormatter(
 *   id = "rating_stars",
 *   label = @Translation("Rating stars"),
 *   field_types = {
 *     "decimal"
 *   }
 * )
 */
class RatingStars extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return array(
      // Implement default settings.
    ) + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    return array(
      // Implement settings form.
    ) + parent::settingsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    // Implement settings summary.

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    $max = $this->getFieldSettings()['max'];
    
    foreach ($items as $delta => $item) {
      $rating = round($item->value / $max * 100);

      // Do it via twig template, because otherwise the style attribute will be
      // stripped off if we just provide #markup here.
      $elements[$delta] = [
        '#theme' => 'rating_stars',
        '#rating' => $rating,
        '#attached' => ['library' => ['rating_formatter/stars']],
      ];
    }

    return $elements;
  }

}
