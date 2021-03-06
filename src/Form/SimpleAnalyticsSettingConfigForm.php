<?php

namespace Drupal\simple_analytics_custom\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a form for simple analytics settings.
 */
class SimpleAnalyticsSettingConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() 
  {
    return 'simple_analytics_custom_settings';
  }
  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() 
  {
    return array(
      'simple_analytics_custom.settings',
    );
  }
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) 
  {
    $form['#attached']['library'][] = 'simple_analytics_custom/simple_analytics_custom.custom';
    \Drupal::service('cache.render')->invalidateAll();
    $config = $this->config('simple_analytics_custom.settings');
    $form['automated_events'] = [
      '#type' => 'details',
      '#title' => $this->t('Automated Events <a href="https://docs.simpleanalytics.com/automated-events">(docs)</a>'),
      '#weight' => 1,
      '#open' => TRUE,
    ];
    $form['automated_events']['auto_container'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Automated Events'),
    ];
    $form['automated_events']['auto_container']['collected_automated_events'] = array(
    '#type'    => 'checkbox',
    '#default_value' => 0,
    '#description_display' => 'before',
    '#description' => 'It will track outbound links, email addresses clicks, and amount of download files (pdf,csv,docx,xlsx). Events will be appear on events page on simpleanalytics.com. Default: on',
    '#title'   => t('Collect Automated Events'),
    '#default_value' => $config->get('collected_automated_events'),
    );
    $form['advanced_settings']['custom_domain'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Custom domain <a href="https://docs.simpleanalytics.com/bypass-ad-blockers">(docs)</a>'),
      '#required' => FALSE,
      '#description_display' => 'before',
      '#description' => 'Are you running your domain on different domain than what is listed in Simple Analytics? Custom your domain here. Default: empty',
      '#default_value' => $config->get('custom_domain'),
    ];
    $form['#cache']['contexts'][] = 'session';
    $form['#cache'] = ['max-age' => 0];
    $form_state->setCached(FALSE);
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) 
  {
      \Drupal::service('cache.render')->invalidateAll();
      $config =  $this->config('simple_analytics_custom.settings');
      $dataExtensions = explode(",", $form_state->getValue('data_extensions'));
      foreach ($dataExtensions as $key1 => $value1) {
        $dataExtensions[$key1] = $value1;
      }
      $config =  $this->config('simple_analytics_custom.settings');
      $config
      ->set('custom_domain', $form_state->getValue('custom_domain'))
      ->set('collected_automated_events', $form_state->getValue('collected_automated_events'))
      ->set('outbound_links', $form_state->getValue('outbound_links'))
      ->set('downloads', $form_state->getValue('downloads'))
      ->set('email_clicks', $form_state->getValue('email_clicks'))
      ->set('data_extensions', $dataExtensions)
      ->save();
       parent::submitForm($form, $form_state);
   
  }

}
