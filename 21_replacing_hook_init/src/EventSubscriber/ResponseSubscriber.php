<?php

namespace Drupal\http_header\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\Core\Session\AccountProxy;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class ResponseSubscriber.
 *
 * @package Drupal\http_header
 */
class ResponseSubscriber implements EventSubscriberInterface {

  /**
   * Current user.
   *
   * @var AccountProxy
   */
  protected $currentUser;

  /**
   * Constructor.
   */
  public function __construct(AccountProxy $current_user) {
    $this->currentUser = $current_user;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::RESPONSE] = ['addAcao'];

    return $events;
  }

  /**
   * Adds Access-Control-Allow-Origin HTTP header for anonymous users.
   *
   * @param FilterResponseEvent $event
   *   Event.
   */
  public function addAcao(FilterResponseEvent $event) {
    if (!$this->currentUser->id()) {
      $response = $event->getResponse();
      $response->headers->set('Access-Control-Allow-Origin', '*');
    }
  }

}
