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
  protected $current_user;

  /**
   * Constructor.
   */
  public function __construct(AccountProxy $current_user) {
    $this->current_user = $current_user;
  }

  /**
   * {@inheritdoc}
   */
  static function getSubscribedEvents() {
    $events[KernelEvents::RESPONSE] = ['addAcao'];

    return $events;
  }

  /**
   * Adds Access-Control-Allow-Origin HTTP header for anonymous users.
   *
   * @param FilterResponseEvent $event
   */
  public function addAcao(FilterResponseEvent $event) {
    if (!$this->current_user->id()) {
      $response = $event->getResponse();
      $response->headers->set('Access-Control-Allow-Origin', '*');
    }
  }

}
