<?php

namespace Drupal\simple_analytics_custom\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a form for simple analytics settings.
 */
class SimpleAnalyticsAdvancedConfigForm extends ConfigFormBase {

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
    $form['advanced_settings'] = array(
      '#type' => 'details',
      '#title' => $this->t('Advanced Settings'),
      '#open' => TRUE,
    );
    $form['advanced_settings']['container'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('Do Not Track Visits <a href="https://docs.simpleanalytics.com/dnt">(docs)</a>'),
      '#open' => TRUE,
    );
    $form['advanced_settings']['container']['do_not_track_visits'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Do Not Track Visits'),
      '#required' => FALSE,
      '#description_display' => 'before',
      '#description' => 'The Do Not Track setting requests that a web application disables either its tracking or cross-site tracking of an individual user.We do not do that ever,so you can select to collect those visits as well. Default: off ',
      '#default_value' => $config->get('do_not_track_visits'),
    ];
     $form['advanced_settings']['ignore_container'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Ignore Admins <a href="https://docs.simpleanalytics.com/create-plugin">(docs)</a>'),
    ];
     $form['advanced_settings']['ignore_container']['ignore_admin'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Ignore Admins'),
      '#required' => FALSE,
      '#description_display' => 'before',
      '#description' => 'When Drupal admins of the website are logged in,should we ignore them.If you check the box drupal (you) will be ignored. Default:On',
      '#default_value' => $config->get('ignore_admin'),
    ];
    $form['advanced_settings']['data_ignore_pages'] = array(
      '#type' => 'textarea',
      '#description_display' => 'before',
      '#title' => $this->t('Ignore Pages <a href="https://docs.simpleanalytics.com/ignore-pages">(docs)</a>'),
      '#description' => 'Not want to run Simple Analytics on certain pages? Enter them here.You can use asterisks to use on multiple pages.Default:empty.',
      '#default_value' => $config->get('data_ignore_pages'),
    );
     $form['advanced_settings']['hash_container'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Hash Mode <a href="https://docs.simpleanalytics.com/hash-mode">(docs)</a>'),
    ];
    $form['advanced_settings']['hash_container']['hash_mode'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Hash Mode'),
      '#required' => FALSE,
      '#description_display' => 'before',
      '#description' => 'If your website use hash (#) navigation, turn this on.On most drupal websites this is not relevant. Default empty',
      '#default_value' => $config->get('hash_mode'),
    ];
    $form['advanced_settings']['collect_pages_container'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Collect Page views <a href="https://docs.simpleanalytics.com/trigger-custom-page-views#use-custom-collection-anyway">(docs)</a>'),
    ];
    $form['advanced_settings']['collect_pages_container']['collect_page_views'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Collect Page views'),
      '#required' => FALSE,
      '#description_display' => 'before',
      '#description' => 'This is required to collect page views.If you only want to collect events,you can turn this feature off.Default:on.',
      '#default_value' =>  $config->get('collect_page_views'),
    ];
 
    $num_names = $form_state->get('num_names');
    $extraSettingsUnserializeCount = 1;
    $extraSettingsValues =  $config->get('extrasettings');
    if(isset($extraSettingsValues) && !empty($extraSettingsValues)){
     $extraSettingsUnserialize =  unserialize($extraSettingsValues);
     $extraSettingsUnserializeCount = count($extraSettingsUnserialize);
    }
    
    if ($num_names === NULL) {
      $name_field = $form_state->set('num_names',$extraSettingsUnserializeCount);
      $num_names = $extraSettingsUnserializeCount;
    }else{
      if($num_names != $extraSettingsUnserializeCount){
        $name_field = $form_state->set('num_names', $num_names);
        $num_names = $num_names;
      }
    }
    // Gather the number of names in the form already.
    
    // We have to ensure that there is at least one name field.
    
    $form['extra_settings'] = array(
          '#type' => 'details',
          '#title' => $this->t('Custom Settings'),
          '#description' => 'Some settings that are newer in Simple Analytics that in this plugin you can add here.Default:empty.',
          '#open' => FALSE,
        );
    $form['extra_settings']['#tree'] = TRUE;
    $form['extra_settings']['fieldset'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Extra Settings'),
      '#prefix' => '<div id="names-fieldset-wrapper">',
      '#suffix
      ' => '</div>',
    ];
    $removed_fields = $form_state->get('removed_fields');
    // If no fields have been removed yet we use an empty array.
    if ($removed_fields === NULL) {
      $form_state->set('removed_fields', array());
      $removed_fields = $form_state->get('removed_fields');
    }
    for ($i = 0; $i < $num_names; $i++) {
       if (in_array($i, $removed_fields)) {
        // Skip if field was removed and move to the next field
        continue;
      }
      $form['extra_settings']['fieldset']['settings'][$i] = [
        '#type' => 'container',
      ];
      $form['extra_settings']['fieldset']['settings'][$i]['key'] = [
        '#type' => 'textfield',
       '#attributes' => array(
        'placeholder' => t('Setting name..'),
      ),
         '#default_value' => (isset($extraSettingsUnserialize) && !empty($extraSettingsUnserialize)) ? $extraSettingsUnserialize[$i]['key'] : '',
      ];
       $form['extra_settings']['fieldset']['settings'][$i]['value'] = [
        '#type' => 'textfield',
        '#attributes' => array(
        'placeholder' => t('Setting key..'),
      ),
         '#default_value' => (isset($extraSettingsUnserialize) && !empty($extraSettingsUnserialize)) ? $extraSettingsUnserialize[$i]['value'] : '',
      ];
      // if($i > 0){
         $form['extra_settings']['fieldset']['settings'][$i]['actions'] = [
          '#type' => 'submit',
          '#value' => $this->t('Remove'),
          '#name' => $i,
          '#submit' => ['::removeCallback'],
          '#ajax' => [
            'callback' => '::addmoreCallback',
            'wrapper' => 'names-fieldset-wrapper',
          ],
        ];
      // }
      
    }

    $form['extra_settings']['fieldset']['actions'] = [
      '#type' => 'actions',
    ];
    $form['extra_settings']['fieldset']['actions']['add_name'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add'),
      '#submit' => ['::addOne'],
      '#ajax' => [
        'callback' => '::addmoreCallback',
        'wrapper' => 'names-fieldset-wrapper',
      ],
    ];
    $form['#cache'] = ['max-age' => 0];
    return parent::buildForm($form, $form_state);
  }
  /**
   * {@inheritdoc}
   */
    public function removeCallback(array &$form, FormStateInterface $form_state) {
    // We use the name of the remove button to find the element we want to remove
    // Line 72: '#name' => $i,
    $trigger = $form_state->getTriggeringElement();
    $indexToRemove = $trigger['#name'];

    // Remove the fieldset from $form (the easy way)
    unset($form['extra_settings']['fieldset']['settings'][$indexToRemove]);

    $removed_fields = $form_state->get('removed_fields');
    $removed_fields[] = $indexToRemove;
    $form_state->set('removed_fields', $removed_fields);

    // Rebuild form_state
    $form_state->setRebuild();
  }
  public function submitForm(array &$form, FormStateInterface $form_state) 
  {
    \Drupal::service('cache.render')->invalidateAll();
       $values = $form_state->getValues();
       $extraSettings = '';
       if(isset($values['extra_settings']['fieldset']['settings']) && !empty($values['extra_settings']['fieldset']['settings'])){
        $extraSettings = serialize($values['extra_settings']['fieldset']['settings']);
       }
     
      $config =  $this->config('simple_analytics_custom.settings');
      $dataIgnorePages =  $form_state->getValue('data_ignore_pages');
      if(is_array($dataIgnorePages)) {
        foreach($dataIgnorePages as $key2 => $value2) {
        $dataIgnorePages[$key2] = $value2;
        }
      }
      $dataIgnore = serialize($dataIgnorePages);
      $dataIgnores = unserialize($dataIgnore);
      $config
      ->set('data_ignore_pages', $dataIgnores)
      ->set('do_not_track_visits', $form_state->getValue('do_not_track_visits'))
      ->set('collect_page_views', $form_state->getValue('collect_page_views'))
      ->set('hash_mode', $form_state->getValue('hash_mode'))
      ->set('ignore_admin', $form_state->getValue('ignore_admin'))
      ->set('extrasettings', $extraSettings)
      ->save();
       parent::submitForm($form, $form_state);
   
  }
    /**
   * Callback for both ajax-enabled buttons.
   *
   * Selects and returns the fieldset with the names in it.
   */
  public function addmoreCallback(array &$form, FormStateInterface $form_state) {
    return $form['extra_settings']['fieldset'];
  }

  /**
   * Submit handler for the "add-one-more" button.
   *
   * Increments the max counter and causes a rebuild.
   */
  public function addOne(array &$form, FormStateInterface $form_state) {
    $name_field = $form_state->get('num_names');
    $add_button = $name_field + 1;
    $form_state->set('num_names', $add_button);
    $form_state->setRebuild();
  }

}
