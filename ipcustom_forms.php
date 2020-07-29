<?php
/**
 * Plugin Name: Infernus Presence Custom Forms
 * Description: Плагин, реализующий обработку форм.
 * Version: 0.15
 * Author: Дмитрий Шумилин
 * Author URI: mailto://dmitri.shumilinn@yandex.ru
 */

require_once plugin_dir_path(__FILE__).'IPFModel.php';
require_once plugin_dir_path(__FILE__).'ipcustom_forms_funcs.php';

add_action('wp_enqueue_scripts', function() {

    wp_enqueue_script('ipcustom_forms_script', plugin_dir_url(__FILE__).'js/ipcustom_forms_script.js');

    wp_enqueue_style('ipcustom_forms_style', plugin_dir_url(__FILE__).'css/ipcustom_forms_style.css');

});

$ipcustom_forms_model = new IPFModel(DB_NAME);

if (!$ipcustom_forms_model->check_table('form_subscribers') || !$ipcustom_forms_model->check_table('form_letters')) {

    if (!$ipcustom_forms_model->create_tables()) wp_die('Something is wrong with DB!', 'Database error');

}

add_action('rest_api_init', function() {

    register_rest_route('ipcustom/v1/forms/', '/subscribe', [
        'methods' => 'POST',
        'callback' => function() {



        },
        'permission_callback' => 'ipcustom_forms_permission'
    ]);

});

add_action('rest_api_init', function() {

    register_rest_route('ipcustom/v1/forms/', '/contact', [
        'methods' => 'POST',
        'callback' => function() {

            

        },
        'permission_callback' => 'ipcustom_forms_permission'
    ]);

});
