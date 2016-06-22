<?php

namespace Drupal\contact_entities\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Contact edit forms.
 *
 * @ingroup contact_entities
 */
class ContactForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;
    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Contact.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Contact.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.contact.canonical', ['contact' => $entity->id()]);
  }

}
