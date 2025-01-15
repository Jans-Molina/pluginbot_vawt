<?php

/**
 * Plugin name: VAWT Chatbot
 * Plugin URI: https://example.com
 * Description: Un chatbot básico que responde preguntas.
 * Version: 1.0.0
 * Author: Jans Molina
 * Author URI: https://github.com/Jans-Molina
 */

if (! defined('WPINC')) {
    die;
} // verificacion de seguridad para que el archivo se ejecute solomente en wordpress.

class VAWT_CB_Chatbot
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'vawt_cb_enqueue_scripts']);

        add_shortcode('vawt_cb_chatbot', [$this, 'vawt_cb_render_chatbot']);

        add_action('wp_ajax_vawt_cb_chatbot_response', [$this, 'vawt_cb_handle_response']);
        add_action('wp_ajax_nopriv_vawt_cb_chatbot_response', [$this, 'vawt_cb_handle_response']);
    }

    public function vawt_cb_enqueue_scripts()
    {
        wp_enqueue_style('pluginbot-vawt-css', plugin_dir_url(__FILE__) . 'css/pluginbot-vawt.css');
        wp_enqueue_script('pluginbot-vawt-js', plugin_dir_url(__FILE__) . 'js/pluginbot-vawt.js', ['jquery'], false, true);

        wp_localize_script('vawt_cb-chatbot-js', 'vawt_cb_chatbot_ajax', [
            'url' => admin_url('admin-ajax.php'),
        ]);
    }

    public function vawt_cb_render_chatbot()
    {
        ob_start();
?>
        <div id="vawt_cb-chatbot-container">
            <div id="vawt_cb-chatbot-messages"></div>
            <input type="text" id="vawt_cb-chatbot-input" placeholder="Escribe tu pregunta..." />
            <button id="vawt_cb-chatbot-send">Enviar</button>
        </div>
<?php
        return ob_get_clean();
    }

    public function vawt_cb_handle_response()
    {
        $question = isset($_POST['question']) ? sanitize_text_field($_POST['question']) : '';
        $response = $this->vawt_cb_generate_response($question);
        wp_send_json(['response' => $response]);
    }

    private function vawt_cb_generate_response($question)
    {
        $responses = [
            'hola' => '¡Hola! ¿Cómo puedo ayudarte?',
            'adiós' => '¡Hasta luego!',
            'precio' => 'Nuestros precios dependen del producto. Visita nuestra página para más información.',
        ];

        $question_lower = strtolower($question);
        return isset($responses[$question_lower]) ? $responses[$question_lower] : 'Lo siento, no entiendo la pregunta.';
    }
}

new VAWT_CB_Chatbot();
