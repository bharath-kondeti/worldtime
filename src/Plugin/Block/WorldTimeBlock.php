<?php

namespace Drupal\worldtime\Plugin\Block;

use Drupal\worldtime\WorldTime;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Datetime\DrupalDateTime;

/**
 * CurrentTime Block.
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
   * The world time service.
   *
   * @var \Drupal\worldtime\WorldTime
   */
  protected $getTime;

  /**
   * Constructs CurrentTimeBlock Object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The config factory service.
   * @param \Drupal\worldtime\WorldTime $getTime
   *   The world time service.
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
  public function build() {
    $getCurrentTime = DrupalDateTime::createFromFormat('dS M Y - h:i A', $this->getTime->getTimeFromTz());
    $renderable = [
      '#theme' => 'world_time',
      '#cache' => [
        'contexts' => ['url', 'user'],
        'tags' => ['world_time_block'],
        'max-age' => 0,
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

}
