<?php
/**
 * Plugin Name: UTM Cookies Tracking
 * Plugin URI: https://github.com/repskyi/utm-cookies-form
 * Description: Модуль відправляє код рекламної кампанії із UTM у Contact Form 7, WPForm Lite
 * Version: 1.2
 * Author: V.Repskyi
 * License: GPL2
 */

function utm_cookies_add_menu() {
        add_options_page('UTM Cookies Module Settings', 'UTM Cookies', 'manage_options', 'utm-cookies-module', 'utm_cookies_form_module_page');
    }
    
    add_action('admin_menu', 'utm_cookies_add_menu');
    add_action('admin_init', 'utm_cookies_save_settings');
    add_filter('plugin_action_links', 'add_utm_cookies_form_settings_link', 10, 2 );

function add_utm_cookies_form_settings_link( $links, $file ) {
        if ( plugin_basename( __FILE__ ) === $file ) {
            $settings_link = '<a href="' . admin_url( 'options-general.php?page=utm-cookies-module' ) . '">' . esc_html__( 'Settings', 'textdomain' ) . '</a>';
            array_push( $links, $settings_link );
        }
        return $links;
    }

function enqueue_utm_cookies_module_script() {
        if (isset($_GET['page']) && $_GET['page'] === 'utm-cookies-module') {
            wp_enqueue_script( 'utm-time-converter-script', plugin_dir_url( __FILE__ ) . 'js/utm-time-convert.js', array(), '1.0', true );
        }
    }
    add_action( 'admin_enqueue_scripts', 'enqueue_utm_cookies_module_script' );

function delete_saved_variables_on_deactivation() {
    delete_option('utm_field_name');
    delete_option('utm_source_time');
    delete_option('utm_medium_time');
    delete_option('utm_term_time');
    delete_option('utm_content_time');
    delete_option('utm_campaign_time');
    delete_option('contact_form_integration_enable');
    delete_option('wpforms_lite_integration_enable');
}
register_deactivation_hook( __FILE__, 'delete_saved_variables_on_deactivation' );

function delete_saved_variables_on_disable() {
    delete_option('utm_field_name');
    delete_option('utm_source_time');
    delete_option('utm_medium_time');
    delete_option('utm_term_time');
    delete_option('utm_content_time');
    delete_option('utm_campaign_time');
    delete_option('contact_form_integration_enable');
    delete_option('wpforms_lite_integration_enable');
}
add_action('switch_theme', 'delete_saved_variables_on_disable');

function utm_cookies_form_module_page() {
        $utm_field_name = get_option('utm_field_name', 'Додаткові параметри рекламної кампанії:');
        $utm_source = get_option('utm_source_time');
        $utm_medium = get_option('utm_medium_time');
        $utm_term = get_option('utm_term_time');
        $utm_content = get_option('utm_content_time');
        $utm_campaign = get_option('utm_campaign_time');
    
        $contact_form_integration_enable = get_option('contact_form_integration_enable', 0);
        $wpforms_lite_integration_enable = get_option('wpforms_lite_integration_enable', 0);

        ?>
        <div class="wrap">
            <h2>Налаштування UTM Cookies Module</h2>
            <form method="post" action="">
                <h3>Введіть час життя cookies для значень UTM у сесії користувача</h3>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Назва розділу UTM:</th>
                        <td><input type="text" name="utm_field_name" value="<?php echo esc_attr($utm_field_name); ?>" style="width: 300px" title="Це поле повинно містити лише цифри" >
                        <span id="utm_field_name"></span>
                    </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">utm_source:</th>
                        <td><input type="text" name="utm_source_time" value="<?php echo esc_attr($utm_source); ?>" pattern="\d*" title="Це поле повинно містити лише цифри" >
                        <span id="utm_source_time"></span>
                    </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">utm_medium:</th>
                        <td><input type="text" name="utm_medium_time" value="<?php echo esc_attr($utm_medium); ?>" pattern="\d*" title="Це поле повинно містити лише цифри" >
                        <span id="utm_medium_time"></span>
                    </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">utm_term:</th>
                        <td><input type="text" name="utm_term_time" value="<?php echo esc_attr($utm_term); ?>" pattern="\d*" title="Це поле повинно містити лише цифри" ></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">utm_content:</th>
                        <td><input type="text" name="utm_content_time" value="<?php echo esc_attr($utm_content); ?>" pattern="\d*" title="Це поле повинно містити лише цифри" ></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">utm_campaign:</th>
                        <td><input type="text" name="utm_campaign_time" value="<?php echo esc_attr($utm_campaign); ?>" pattern="\d*" title="Це поле повинно містити лише цифри" ></td>
                    </tr>
                    <?php if ( is_plugin_active('contact-form-7/wp-contact-form-7.php') ) { ?>
                    <tr valign="top">
                    <th scope="row">Підключити відправку utm даних у Contact Form 7</th>
                    <td>
                        <label for="contact_form_integration_enable">    
                            <input type="checkbox" id="contact_form_integration_enable" name="contact_form_integration_enable" value="1" <?php checked($contact_form_integration_enable, '1'); ?>> 
                            Включити
                        </label>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php if ( is_plugin_active('wpforms-lite/wpforms.php') ) { ?>
                    <tr valign="top">
                    <th scope="row">Підключити відправку utm даних у WPForm Lite</th>
                    <td>
                        <label for="wpforms_lite_integration_enable">
                            <input type="checkbox" id="wpforms_lite_integration_enable" name="wpforms_lite_integration_enable" value="1" <?php checked($wpforms_lite_integration_enable, '1'); ?>>  
                            Включити
                        </label>
                        <p>Якщо потрібно щоб дані приходили у листі добавте у поле Email Message змінну Smart tag <strong>{utmdata}</strong></p>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
                <?php submit_button('Зберегти'); ?>
            </form>
        </div>
        <?php
    }
    
    // Обробник для збереження даних форми
    function utm_cookies_save_settings() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
            if (isset($_POST['utm_field_name'])) {
                update_option('utm_field_name', sanitize_text_field($_POST['utm_field_name']));
            }
            if (isset($_POST['utm_source_time'])) {
                update_option('utm_source_time', sanitize_text_field($_POST['utm_source_time']));
            }
            if (isset($_POST['utm_medium_time'])) {
                update_option('utm_medium_time', sanitize_text_field($_POST['utm_medium_time']));
            }
            if (isset($_POST['utm_term_time'])) {
                update_option('utm_term_time', sanitize_text_field($_POST['utm_term_time']));
            }
            if (isset($_POST['utm_content_time'])) {
                update_option('utm_content_time', sanitize_text_field($_POST['utm_content_time']));
            }
            if (isset($_POST['utm_campaign_time'])) {
                update_option('utm_campaign_time', sanitize_text_field($_POST['utm_campaign_time']));
            }
            if (isset($_POST['contact_form_integration_enable'])) {
                update_option('contact_form_integration_enable', $_POST['contact_form_integration_enable']);
            } else {
                update_option('contact_form_integration_enable', '0');
            }
            if (isset($_POST['wpforms_lite_integration_enable'])) {
                update_option('wpforms_lite_integration_enable', $_POST['wpforms_lite_integration_enable']);
            } else {
                update_option('wpforms_lite_integration_enable', '0');
            }
        }
    }
 
    if (get_option('wpforms_lite_integration_enable') == '1') {
        add_filter('wpforms_process_filter', 'track_wpforms_submission_with_utm_data', 10, 3);
        add_filter('wpforms_email_display_empty_fields', '__return_true' );
        add_filter('wpforms_smart_tags', 'utm_data_smarttag', 10, 1 );
        add_filter('wpforms_smart_tag_process', 'utm_data_process_smarttag', 10, 2 );
    }
    if (get_option('contact_form_integration_enable') == '1') {
        add_filter( 'wpcf7_posted_data', 'add_custom_variables_to_form', 9, 1 );
        add_action('wpcf7_before_send_mail', 'add_utm_values_to_cf7_message');
    }

    class UTM_PARAMS {
        public $utm_source_cookie;
        public $utm_medium_cookie;
        public $utm_term_cookie;
        public $utm_content_cookie;
        public $utm_campaign_cookie;
    }
    function save_utm_params_to_cookie() {
        $utm = new UTM_PARAMS();

        $utm->utm_source_cookie = isset($_GET["utm_source"]) ?  htmlspecialchars( $_GET["utm_source"]) : '';
        $utm->utm_medium_cookie = isset($_GET["utm_medium"]) ?  htmlspecialchars( $_GET["utm_medium"]) : '';
        $utm->utm_term_cookie = isset($_GET['utm_term']) ?  htmlspecialchars( $_GET["utm_term"]) : '';
        $utm->utm_content_cookie = isset($_GET['utm_content']) ?  htmlspecialchars( $_GET["utm_content"]) : '';
        $utm->utm_campaign_cookie = isset($_GET['utm_campaign']) ?  htmlspecialchars( $_GET["utm_campaign"]) : '';
        
        $utm_source_time   = time() + intval(get_option('utm_source_time', ''));
        $utm_medium_time   = time() + intval(get_option('utm_medium_time', ''));
        $utm_term_time     = time() + intval(get_option('utm_term_time', ''));
        $utm_content_time  = time() + intval(get_option('utm_content_time', ''));
        $utm_campaign_time = time() + intval(get_option('utm_campaign_time', ''));

        // Якщо значення в куках відсутні, використовуємо значення за замовчуванням

        if (!empty($utm->utm_source_cookie)) {
            setcookie('utm_source', $utm->utm_source_cookie, $utm_source_time, '/');
        }
        if (!empty($utm->utm_medium_cookie)) {
            setcookie('utm_medium', $utm->utm_medium_cookie, $utm_medium_time, '/');
        }
        if (!empty($utm->utm_term_cookie)) { 
            setcookie('utm_term', $utm->utm_term_cookie, $utm_term_time, '/');
        }
        if (!empty($utm->utm_content_cookie)) {
            setcookie('utm_content', $utm->utm_content_cookie, $utm_content_time, '/');
        }   
        if (!empty($utm->utm_campaign_cookie)) {
            setcookie('utm_campaign', $utm->utm_campaign_cookie, $utm_campaign_time, '/');
        }
    }
    
    add_action('init', 'save_utm_params_to_cookie');    


    function get_utm_data() {
        $utm = new UTM_PARAMS();

        $utm->utm_source_cookie = isset($_COOKIE['utm_source']) ? $_COOKIE['utm_source'] : '';
        $utm->utm_medium_cookie = isset($_COOKIE['utm_medium']) ? $_COOKIE['utm_medium'] : '';
        $utm->utm_term_cookie = isset($_COOKIE['utm_term']) ? $_COOKIE['utm_term'] : '';
        $utm->utm_content_cookie = isset($_COOKIE['utm_content']) ? $_COOKIE['utm_content'] : '';
        $utm->utm_campaign_cookie = isset($_COOKIE['utm_campaign']) ? $_COOKIE['utm_campaign'] : '';
        
        return $utm;
    }


    function get_utm_name_with_data() {
        $utm = get_utm_data();

        $utm_name_with_data = get_option('utm_field_name') ? get_option('utm_field_name') : "";
        if (!empty($utm->utm_source_cookie)) {
            $utm_name_with_data .= "\nutm_source = " . $utm->utm_source_cookie . "\n";
        }
        if (!empty($utm->utm_medium_cookie)) {
            $utm_name_with_data .= "utm_medium = " . $utm->utm_medium_cookie . "\n";
        }
        if (!empty($utm->utm_term_cookie)) {
            $utm_name_with_data .= "utm_term = " . $utm->utm_term_cookie . "\n";
        }
        if (!empty($utm->utm_content_cookie)) {
            $utm_name_with_data .= "utm_content = " . $utm->utm_content_cookie . "\n";
        }
        if (!empty($utm->utm_campaign_cookie)) {
            $utm_name_with_data .= "utm_campaign = " . $utm->utm_campaign_cookie . "\n";
        }
        return $utm_name_with_data;
    }

    function add_custom_variables_to_form( $posted_data ) {
        $utm_data = get_utm_name_with_data();
        // Додаємо ваші змінні у дані форми
        $_POST['UTM_ADVERTISING_DATA'] = $utm_data;
        
        return $posted_data;
    }
    

    function add_utm_values_to_cf7_message($contact_form) {
        $utm_data = get_utm_name_with_data();

        $mail = $contact_form->prop('mail');
        $mail['body'] .= "\n\n". $utm_data;
        $contact_form->set_properties(array('mail' => $mail));
    
    }
    
    function track_wpforms_submission_with_utm_data($fields, $entry, $form_data) {
        if (get_option('wpforms_lite_integration_enable') == '0') {
            return $fields;
        }
        $utm_data = get_utm_values();
        if (!empty($utm_data)) {
            $fields[] = array(
                'id' => 'utmdata',
                'type' => 'html',
                'name' => "\nДодаткові параметри рекламної кампанії",
                'value' => "".$utm_data,
            );
        } else {
            $fields['utmdata'] = array(
                'label' => 'UTM Data',
                'value' => $utm_data,
            );
        }

        return $fields;
    }

    function utm_data_smarttag( $tags ) {
        $tags[ 'utmdata' ] =  'UTM Data';
     
        return $tags;
    }
    
    function utm_data_process_smarttag( $content, $tag ) {
        if ( 'utmdata' === $tag ) {
            $utm_data = "\n<strong>Додаткові параметри рекламної кампанії:</strong>\n" . get_utm_values();
            $content = str_replace( '{utmdata}', $utm_data, $content );
        }

        return $content;
    }

?>