<?php

namespace Drupal\worldtime;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Datetime\DateFormatterInterface;

/**
 * Service for getting Time from selected timezone.
 */
class WorldTime {

  /**
   * The config factory service.
   * 
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

  /**
   * The date formatter service.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * The time service.
   *
   * @var \Drupal\Component\Datetime\TimeInterface
   */
  protected $time;

  /**
   * Construts GetTimeFromTz service.
   * 
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The config factory.
   */
  public function __construct(ConfigFactoryInterface $configFactory, DateFormatterInterface $dateFormatter, TimeInterface $time) {
    $this->config = $configFactory->get('worldtime.settings');
    $this->dateFormatter = $dateFormatter;
    $this->time = $time;
  }

  /**
   * @return string
   *   A date string with requested format.
   */
  public function getTimeFromTz() {
    return $this->dateFormatter->format($this->time->getCurrentTime(), 'custom', 'dS M Y - h:i A', $this->config->get('tz_timezone'));
  }
}
