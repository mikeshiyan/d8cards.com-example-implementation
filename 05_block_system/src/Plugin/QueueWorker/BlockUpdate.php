<?php

namespace Drupal\serc_updater\Plugin\QueueWorker;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use GuzzleHttp\Client;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Queue Worker that actualizes Stock Exchange Rate Cards.
 *
 * @QueueWorker(
 *   id = "serc_updater",
 *   title = @Translation("Stock Exchange Rate Cards Updater"),
 *   cron = {"time" = 120}
 * )
 */
class BlockUpdate extends QueueWorkerBase implements ContainerFactoryPluginInterface {

  /**
   * Blocks entity storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $blockStorage;

  /**
   * HTTP client.
   *
   * @var \GuzzleHttp\Client
   */
  protected $httpClient;

  /**
   * BlockUpdate constructor.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param array $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityStorageInterface $block_storage
   *   The block entity storage.
   * @param \GuzzleHttp\Client $http_client
   *   HTTP client.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityStorageInterface $block_storage, Client $http_client) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->blockStorage = $block_storage;
    $this->httpClient = $http_client;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity.manager')->getStorage('block_content'),
      $container->get('http_client')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function processItem($data) {
    /** @var BlockContentInterface $block */
    $block = $this->blockStorage->load($data);

    $symbol = $block->get('field_symbol')->value;
    $data = $this->getSercData($symbol);

    $block->set('field_last_price', $data['LastPrice']);
    $block->set('field_change', $data['Change']);

    return $block->save();
  }

  /**
   * Gets stock exchange rate data.
   *
   * @param string $symbol
   *   Company symbol.
   *
   * @return array
   *   Associative array with data.
   *
   * @throws \Exception
   *   If response from API is broken.
   */
  protected function getSercData($symbol) {
    $url = 'http://dev.markitondemand.com/MODApis/Api/v2/Quote/jsonp?symbol=' . $symbol . '&callback=myFunction';
    $response = $this->httpClient->request('GET', $url);

    if ($response->getStatusCode() == 200) {
      $response = (string) $response->getBody();

      if (strpos($response, 'myFunction({') === 0) {
        $response = substr($response, 11, -1);
        $response = @json_decode($response, TRUE);

        if (isset($response['Status']) && $response['Status'] == 'SUCCESS') {
          return $response;
        }
      }
    }

    throw new \Exception('Something wrong with response from dev.markitondemand.com');
  }

}
