<?php
/**
 * Plugin Name: Infernus Presence Custom Forms
 * Description: Плагин, реализующий обработку форм.
 * Version: 0.5
 * Author: Дмитрий Шумилин
 * Author URI: mailto://dmitri.shumilinn@yandex.ru
 */

require_once plugin_dir_path(__FILE__).'IPFModel.php';
require_once plugin_dir_path(__FILE__).'ipcustom_forms_funcs.php';

$ipcustom_forms_pool = array_merge(range(0, 0), range('a', 'z'));

$ipcustom_forms_key = '';

for ($i = 0; $i < 50; $i++) {
    
    $ipcustom_forms_key .= $ipcustom_forms_pool[random_int(0, count($ipcustom_forms_pool) - 1)];

}

$ipcustom_forms_key_storage = 'ipcustom_forms_key_'.time();

session_start(['name' => 'ipcustom_forms_session']);

if (session_status() === PHP_SESSION_ACTIVE) $_SESSION[$ipcustom_forms_key_storage] = $ipcustom_forms_key;
else wp_die('Something is wrong with session!', 'Session failure');

add_action('wp_enqueue_scripts', function() {

    wp_enqueue_script('ipcustom_forms_script', plugin_dir_url(__FILE__).'js/ipcustom_forms_script.js');

    wp_enqueue_style('ipcustom_forms_style', plugin_dir_url(__FILE__).'css/ipcustom_forms_style.css');

});

add_filter('the_content', function($content) {

    global $ipcustom_forms_key;
    global $ipcustom_forms_key_storage;

    $content .= '<input type="hidden" id="ipf_key" name="ipf_key" value="'.$ipcustom_forms_key.'"><input type="hidden" id="ipf_key_storage" name="ipf_key_storage" value="'.$ipcustom_forms_key_storage.'">';

    return $content;

});

$ipcustom_forms_model = new IPFModel(DB_NAME);

if (!$ipcustom_forms_model->check_table('form_subscribers') || !$ipcustom_forms_model->check_table('form_letters')) {

    if (!$ipcustom_forms_model->create_tables()) wp_die('Something is wrong with DB!', 'Database error');

}

add_action('rest_api_init', function() {

    register_rest_route('ipcustom/v1', '/forms/subscribe', [
        'methods' => 'POST',
        'callback' => function() {

            if (isset($_POST['email'])) {

                $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

                    global $ipcustom_forms_model;

                    if ($ipcustom_forms_model->create_subscriber($email)) $result = ['code' => '0', 'message' => 'Success.'];
                    else $result = ['code' => '-3', 'message' => 'Database query failure.'];

                } else $result = ['code' => '-2', 'message' => 'Bad arguments.'];

            } else $result = ['code' => '-1', 'message' => 'Too few arguments for this request.'];

            return $result;

        },
        'permission_callback' => 'ipcustom_forms_permission'
    ]);

    register_rest_route('ipcustom/v1', '/forms/contact', [
        'methods' => 'POST',
        'callback' => function() {

            if (isset($_POST['email']) && isset($_POST['name']) && isset($_POST['text'])) {

                $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

                $name = htmlspecialchars($_POST['name']);

                $text = htmlspecialchars($_POST['text']);

                if (filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($name) && !empty($text)) {

                    global $ipcustom_forms_model;

                    if ($ipcustom_forms_model->create_letter($email, $name, $text)) $result = ['code' => '0', 'message' => 'Success.'];
                    else $result = ['code' => '-3', 'message' => 'Database query failure.'];

                } else $result = ['code' => '-2', 'message' => 'Bad arguments.'];

            } else $result = ['code' => '-1', 'message' => 'Too few arguments for this request.'];

            return $result;

        },
        'permission_callback' => 'ipcustom_forms_permission'
    ]);

});
