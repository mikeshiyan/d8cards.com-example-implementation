<?php

namespace Drupal\contact_entities\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for Contact entities.
 */
class ContactViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['contact']['table']['base'] = array(
      'field' => 'id',
      'title' => $this->t('Contact'),
      'help' => $this->t('The Contact ID.'),
    );

    return $data;
  }

}
