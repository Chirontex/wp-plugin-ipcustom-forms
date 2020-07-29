<?php
/**
 * Plugin Name: Infernus Presence Custom Forms
 * Description: Плагин, реализующий обработку форм.
 * Version: 0.16
 * Author: Дмитрий Шумилин
 * Author URI: mailto://dmitri.shumilinn@yandex.ru
 */

require_once plugin_dir_path(__FILE__).'IPFModel.php';
require_once plugin_dir_path(__FILE__).'ipcustom_forms_funcs.php';

// пароль, необходимый для защиты от CSRF-атак
// это значение желательно менять после каждой новой установки
define('IPF_PASSWORD', 'zdbsdwb45bew34xdfvhs34dz34segrgfdzdsseh4b');

$ipcustom_forms_hash = password_hash(IPF_PASSWORD, PASSWORD_DEFAULT);

session_start('ipcustom_forms_session');

if (session_status() === PHP_SESSION_ACTIVE) $_SESSION['ipcustom_forms_hash'] = $ipcustom_forms_hash;
else wp_die('Something is wrong with session!', 'Session failure');

add_action('wp_enqueue_scripts', function() {

    wp_enqueue_script('ipcustom_forms_script', plugin_dir_url(__FILE__).'js/ipcustom_forms_script.js');

    wp_enqueue_style('ipcustom_forms_style', plugin_dir_url(__FILE__).'css/ipcustom_forms_style.css');

});

add_filter('the_content', function($content) {

    global $ipcustom_forms_hash;

    $content .= '<input type="hidden" id="ipf_hash" name="ipf_hash" value="'.$ipcustom_forms_hash.'">';

    return $content;

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
