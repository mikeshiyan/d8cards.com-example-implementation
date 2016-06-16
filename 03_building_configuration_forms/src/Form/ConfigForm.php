<?php
/**
 * @file
 * Contains \Drupal\mymodule\Form\ConfigForm.
 */

namespace Drupal\mymodule\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class ConfigForm.
 *
 * @package Drupal\mymodule\Form
 */
class ConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'mymodule.config',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'mymodule_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('mymodule.config');

    $form['a'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('A'),
      '#default_value' => $config->get('a'),
      '#required' => TRUE,
    );

    $form['b'] = array(
      '#type' => 'select',
      '#title' => $this->t('B'),
      '#options' => range(0, 10),
      '#default_value' => $config->get('b'),
    );

    $form['c'] = array(
      '#type' => 'radios',
      '#title' => $this->t('Radio'),
      '#options' => array('Ololo' => $this->t('Ololo'), 'Lorem' => $this->t('Ipsum')),
      '#default_value' => $config->get('c'),
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('mymodule.config')
      ->set('a', $form_state->getValue('a'))
      ->set('b', $form_state->getValue('b'))
      ->set('c', $form_state->getValue('c'))
      ->save();
  }

}
