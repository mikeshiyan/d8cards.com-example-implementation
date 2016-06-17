<?php
/**
 * @file
 * Contains Drupal\welcome_email\Plugin\QueueWorker\WelcomeEmail.
 */

namespace Drupal\welcome_email\Plugin\QueueWorker;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Mail\MailManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\user\UserInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Queue Worker that sends a welcome email to registered users.
 *
 * @QueueWorker(
 *   id = "welcome_email",
 *   title = @Translation("Welcome Email"),
 *   cron = {"time" = 60}
 * )
 */
class WelcomeEmail extends QueueWorkerBase implements ContainerFactoryPluginInterface {

  /**
   * User storage.
   *
   * @var EntityStorageInterface
   */
  protected $userStorage;

  /**
   * Mail manager.
   *
   * @var MailManagerInterface
   */
  protected $mailManager;

  /**
   * WelcomeEmail constructor.
   *
   * @param EntityStorageInterface $user_storage
   *   The user storage.
   * @param MailManagerInterface $mail_manager
   *   Mail manager.
   */
  public function __construct(EntityStorageInterface $user_storage, MailManagerInterface $mail_manager) {
    $this->userStorage = $user_storage;
    $this->mailManager = $mail_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $container->get('entity.manager')->getStorage('user'),
      $container->get('plugin.manager.mail')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function processItem($data) {
    /** @var UserInterface $user */
    $user = $this->userStorage->load($data);

    if ($user) {  
      $this->mailManager->mail('welcome_email', 'welcome', $user->getEmail(), $user->getPreferredLangcode(), ['account' => $user]);
    }
  }

}
