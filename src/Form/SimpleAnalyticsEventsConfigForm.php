<?php

namespace Drupal\simple_analytics_custom\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a form for simple analytics settings.
 */
class SimpleAnalyticsEventsConfigForm extends ConfigFormBase {

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
    $config = $this->config('simple_analytics_custom.settings');
    $form['event_settings'] = array(
      '#type' => 'details',
      '#title' => $this->t('Event Settings'),
      '#open' => FALSE,
    );
    $form['event_settings']['collected_automated_events'] = array(
    '#type'    => 'checkbox',
    '#default_value' => 0,
    '#description_display' => 'before',
    '#description' => 'It will track outbound links, email addresses clicks, and amount of download files(pdf,csv,docx,xlsx).Events will be appear on events page on simpleanalytics.com.Default: on.',
    '#title'   => t('Collect Automated Events'),
    '#default_value' => $config->get('collected_automated_events'),
  );
    $form['event_settings']['outbound_links'] = array(
      '#type'          => 'checkbox',
      '#title'         => t('Outbound Links'),
      '#description_display' => 'before',
      '#description' => 'It will track clicks on links on other websites.',
      '#default_value' => $config->get('outbound_links'),
      '#states'        => array(
         'visible'      => array(
           ':input[name="collected_automated_events"]' => array('checked' => TRUE),
         ),
       ),
    );
    $form['event_settings']['email_clicks'] = array(
      '#type'          => 'checkbox',
      '#title'         => t('Email Clicks'),
      '#description_display' => 'before',
      '#description' => 'It will track clicks on emailadresses.',
      '#default_value' => $config->get('email_clicks'),
      '#states'        => array(
         'visible'      => array(
           ':input[name="collected_automated_events"]' => array('checked' => TRUE),
         ),
       ),
    );
    $form['event_settings']['downloads'] = array(
      '#type'          => 'checkbox',
      '#title'         => t('Downloads'),
      '#default_value' => $config->get('downloads'),
      '#description_display' => 'before',
     '#description' => 'It will track download of certain files.',
      '#states'        => array(
         'visible'      => array(
           ':input[name="collected_automated_events"]' => array('checked' => TRUE),
         ),
       ),
    );
   $form['event_settings']['data_extensions'] = array(
        '#type'          => 'textfield',
        '#title'         => t('Extensions'),
        '#default_value' => $config->get('data_extensions'),
        '#description_display' => 'before',
        '#description' => 'Select the extensions you want to count the download of.',
        '#maxlength' => 500,
        '#states'        => array(
           'visible'      => array(
             ':input[name="downloads"]' => array('checked' => TRUE),
             ':input[name="collected_automated_events"]' => array('checked' => TRUE),
           ),
          'required'      => array(
             ':input[name="collected_automated_events"]' => array('checked' => TRUE),
           ),
         ),
      );
    $form['event_settings']['data_sa_global'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Data sa global'),
      '#required' => FALSE,
      '#default_value' => $config->get('data_sa_global'),
    ];
     $form['event_settings']['data_use_title'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Data Use Title'),
      '#required' => FALSE,
      '#default_value' => $config->get('data_use_title'),
    ];
    $form['event_settings']['data_full_urls'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Data Full Urls'),
      '#required' => FALSE,
      '#default_value' => $config->get('data_full_urls'),
    ];
    $form['#cache'] = ['max-age' => 0];
    return parent::buildForm($form, $form_state);
  }


  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) 
  {
      $config =  $this->config('simple_analytics_custom.settings');
      $dataExtensions = explode(", ", $form_state->getValue('data_extensions'));
    foreach ($dataExtensions as $key1 => $value1) {
      $dataExtensions[$key1] = $value1;
    }
      $config->
      set('data_sa_global', $form_state->getValue('data_sa_global'))
      ->set('data_full_urls', $form_state->getValue('data_full_urls'))
      ->set('data_use_title', $form_state->getValue('data_use_title'))
      ->set('collected_automated_events', $form_state->getValue('collected_automated_events'))
      ->set('automated_events', $form_state->getValue('automated_events'))
      ->set('outbound_links', $form_state->getValue('outbound_links'))
      ->set('email_clicks', $form_state->getValue('email_clicks'))
      ->set('downloads', $form_state->getValue('downloads'))
      ->set('data_extensions', $dataExtensions)
      ->save();
       parent::submitForm($form, $form_state);
   
  }

}
