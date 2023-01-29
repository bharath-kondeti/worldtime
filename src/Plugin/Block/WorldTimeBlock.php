<?php

namespace Drupal\worldtime\Plugin\Block;

use Drupal\worldtime\WorldTime;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Datetime\DrupalDateTime;

/**
 * CurrentTime Block
 * 
 * @Block(
 *   id = "world_time",
 *   admin_label = @Translation("World Time Block"),
 * )
 */
class WorldTimeBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The config factory service.
   * 
   * @var Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $config;

  /**
   * @var \Drupal\worldtime\WorldTime
   */
  protected $getTime;

  /**
   * Constructs CurrentTimeBlock Object.
   *
   * @param array $configuration
   * @param $plugin_id
   * @param $plugin_definition
   * @param ConfigFactoryInterface $configFactory
   * @param WorldTime $getTime
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $configFactory, WorldTime $getTime) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->config = $configFactory->get('worldtime.settings');
    $this->getTime = $getTime;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory'),
      $container->get('worldtime.get_time'),
    );
  }

  /**
   * {@inheritDoc}
   */
  public function build()
  {
    $getCurrentTime = DrupalDateTime::createFromFormat('dS M Y - h:i A', $this->getTime->getTimeFromTz());
    $renderable = [
      '#theme' => 'world_time',
      '#cache' => [
        'contexts' => ['url', 'user'],
        'tags' => ['world_time_block'],
      ],
      '#tz_country' => $this->config->get('tz_country'),
      '#tz_city' => $this->config->get('tz_city'),
      '#tz_time' => $getCurrentTime->getTimestamp(),
      '#attached' => [
        'library' => [
          'worldtime/world-time',
        ],
      ],
    ];

    return $renderable;
  }

  /**
   * {@inheritDoc}
   */
  public function getCacheMaxAge() {
    return 10;
  }

}
