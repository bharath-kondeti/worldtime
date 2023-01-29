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
   * @param \Drupal\Core\Datetime\DateFormatterInterface $dateFormatter
   *   The date formatter service.
   * @param \Drupal\Component\Datetime\TimeInterface $time
   *   The time service.
   */
  public function __construct(ConfigFactoryInterface $configFactory, DateFormatterInterface $dateFormatter, TimeInterface $time) {
    $this->config = $configFactory->get('worldtime.settings');
    $this->dateFormatter = $dateFormatter;
    $this->time = $time;
  }

  /**
   * Returns the date string in the requested format.
   *
   * @return string
   *   A date string with requested format.
   */
  public function getTimeFromTz() {
    return $this->dateFormatter->format($this->time->getCurrentTime(), 'custom', 'dS M Y - h:i A', $this->config->get('tz_timezone'));
  }

}
