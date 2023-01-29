<?php

namespace Drupal\worldtime\Form;

use Drupal\Core\Cache\CacheTagsInvalidatorInterface;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configure timezone settings in this form.
 */
class WorldTimeSettingsForm extends ConfigFormBase
{
  /**
   * The messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * The cache tags invalidator.
   *
   * @var \Drupal\Core\Cache\CacheTagsInvalidatorInterface
   */
  protected $cacheTagsInvalidator;

  /**
   * Constructs the TzSettingsForm object
   * 
   * @param \Drupal\Core\Config\ConfigFactory $config_factory
   *   The config factory.
   * 
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger service.
   * 
   * @param \Drupal\Core\Cache\CacheTagsInvalidatorInterface $cacheTagsInvalidator
   *   The cache tags invalidator.
   */
  public function __construct(ConfigFactory $config_factory, MessengerInterface $messenger, CacheTagsInvalidatorInterface $cacheTagsInvalidator)
  {
    parent::__construct($config_factory);
    $this->messenger = $messenger;
    $this->cacheTagsInvalidator = $cacheTagsInvalidator;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('config.factory'),
      $container->get('messenger'),
      $container->get('cache_tags.invalidator'),
    );
  }

  /**
   * {@inheritDoc}
   */
  protected function getEditableConfigNames()
  {
    return [
      'worldtime.settings'
    ];
  }

  /**
   * {@inheritDoc}
   */
  public function getFormId()
  {
    return 'worldtime_settings_form';
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $config = $this->config('worldtime.settings');
    $form['tz_country'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Country'),
      '#description' => $this->t('Please enter the country name.'),
      '#default_value' => $config->get('tz_country') ? $config->get('tz_country') : 'India',
    ];

    $form['tz_city'] = [
      '#type' => 'textfield',
      '#title' => $this->t('City'),
      '#description' => $this->t('Please enter the city name.'),
      '#default_value' => $config->get('tz_city') ? $config->get('tz_city') : 'Kolkata',
    ];

    $form['tz_timezone'] = [
      '#type' => 'select',
      '#title' => $this->t('Timezone'),
      '#description' => $this->t('Please select the timezone.'),
      '#options' => [
        'America/New_York' => 'America/New_York',
        'America/Chicago' => 'America/Chicago',
        'Asia/Tokyo' => 'Asia/Tokyo',
        'Asia/Dubai' => 'Asia/Dubai',
        'Asia/Kolkata' => 'Asia/Kolkata',
        'Europe/Amsterdam' => 'Europe/Amsterdam',
        'Europe/Oslo' => 'Europe/Oslo',
        'Europe/London' => 'Europe/London',
      ],
      '#default_value' => $config->get('tz_timezone') ? $config->get('tz_timezone') : 'Asia/Kolkata',
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $this->config('worldtime.settings')
      ->set('tz_country', strip_tags($form_state->getValue('tz_country')))
      ->set('tz_city', strip_tags($form_state->getValue('tz_city')))
      ->set('tz_timezone', $form_state->getValue('tz_timezone'))
      ->save();

    $this->cacheTagsInvalidator->invalidateTags(['world_time_block']);
    $this->messenger->addStatus($this->t('The settings have been updated.'));
  }
}
