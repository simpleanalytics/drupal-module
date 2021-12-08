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
    $form['#cache'] = ['max-age' => 0];
    $config = $this->config('simple_analytics_custom.settings');
    // $form['system_plugin_specific_settings'] = array(
    //   '#type' => 'details',
    //   '#title' => $this->t('System plugins specific settings'),
    //   '#open' => FALSE,
    // );

    $form['automated_events'] = [
      '#type' => 'details',
      '#title' => $this->t('Automated Events'),
      '#weight' => 1,
      '#open' => FALSE
      ,
    ];
    $form['automated_events']['collected_automated_events'] = array(
    '#type'    => 'checkbox',
    '#default_value' => 0,
    '#description_display' => 'before',
    '#description' => 'It will track outbound links, email addresses clicks, and amount of download files(pdf,csv,docx,xlsx).Events will be appear on events page on simpleanalytics.com.Default: on.',
    '#title'   => t('Collect Automated Events'),
    '#default_value' => $config->get('collected_automated_events'),
  );
    // $form['system_plugin_specific_settings']['custom_domains'] = [
    //   '#type' => 'textfield',
    //   '#title' => $this->t('Custom Domain'),
    //   '#required' => FALSE,
    //   '#default_value' => $config->get('custom_domains'),
    //   '#description' => $this->t('Please enter the custom domain.For example:simple.example.com'),
    //   '#attributes' => array(
    //     'placeholder' => t('Enter custom domain or leave empty..'),
    //   ),
    // ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) 
  {
      $config =  $this->config('simple_analytics_custom.settings');
      $dataExtensions = explode(",", $form_state->getValue('data_extensions'));
      foreach ($dataExtensions as $key1 => $value1) {
        $dataExtensions[$key1] = $value1;
      }
      $config =  $this->config('simple_analytics_custom.settings');
      $config
      ->set('collected_automated_events', $form_state->getValue('collected_automated_events'))
      ->set('outbound_links', $form_state->getValue('outbound_links'))
      ->set('downloads', $form_state->getValue('downloads'))
      ->set('email_clicks', $form_state->getValue('email_clicks'))
      ->set('data_extensions', $dataExtensions)
      ->save();
       parent::submitForm($form, $form_state);
   
  }

}
