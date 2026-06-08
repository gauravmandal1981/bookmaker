<?php
/**
 * Plugin Name: Odds Comparison Plugin
 * Description: Fetches and displays odds from multiple bookmakers with configurable admin settings.
 * Author: Gaurav Mandal
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define('PLUGIN_CUSTOM_URI_BOOKMARKER', plugin_dir_url( __FILE__ ));
include_once plugin_dir_path( __FILE__ ) . 'fetch_subclass.php';

class Bookmarker 
{
    public function __construct() 
    {
        add_action('admin_menu', [$this, 'admin_menu_funct']);
        add_action( 'admin_enqueue_scripts', [$this, 'bookmarker_enqueue_scripts']);
    }

    public function bookmarker_enqueue_scripts() {
        wp_enqueue_script(
            'bookmarker-common',
            PLUGIN_CUSTOM_URI_BOOKMARKER . 'assets/js/common.js',
            array('jquery'),
            '1.0.0',
            true
        );
    }
    
    //Creating Menu in Admin Panel
    public function admin_menu_funct () 
    {
        add_menu_page(
            'Odds Settings',
            'Odds Settings',
            'manage_options',
            'odds-settings',
            [$this, 'render_odds_settings_page']
        );
    }

    public function render_odds_settings_page()
    {
        if (
            isset($_POST['save_urls']) &&
            check_admin_referer('save_bookmaker_urls')
        ) {

            $urls = [];

            if (!empty($_POST['bookmaker_urls'])) {

                foreach ($_POST['bookmaker_urls'] as $url) {

                    $url = esc_url_raw(trim($url));

                    if (!empty($url)) {
                        $urls[] = $url;
                    }
                }
            }

            update_option(
                'my_bookmaker_urls',
                $urls
            );

            echo '<div class="notice notice-success"><p>Settings saved.</p></div>';
        }

        $urls = get_option(
            'my_bookmaker_urls',
            []
        );

        ob_start();
        ?>
            <h2>Odds Settings</h2>
            <form action="" method="post">

            <?php wp_nonce_field('save_bookmaker_urls'); ?>

            <table class="form-table">
                <tr>
                    <th>Bookmaker URLs</th>
                    <td>

                        <div id="url-repeater">

                            <?php if (!empty($urls)) : ?>

                                <?php foreach ($urls as $url) : ?>

                                    <div class="url-row" style="margin-bottom:10px;">

                                        <input
                                            type="url"
                                            name="bookmaker_urls[]"
                                            value="<?php echo esc_attr($url); ?>"
                                            class="regular-text"
                                        >

                                        <button
                                            type="button"
                                            class="button remove-url"
                                        >
                                            Delete
                                        </button>

                                    </div>

                                <?php endforeach; ?>

                            <?php else : ?>

                                <div class="url-row" style="margin-bottom:10px;">

                                    <input
                                        type="url"
                                        name="bookmaker_urls[]"
                                        class="regular-text"
                                        placeholder="Bookmarker URL"
                                    >

                                    <button
                                        type="button"
                                        class="button remove-url"
                                    >
                                        Delete
                                    </button>

                                </div>

                            <?php endif; ?>

                        </div>

                        <p>
                            <button
                                type="button"
                                id="add-url"
                                class="button button-secondary"
                            >
                                Add URL
                            </button>
                        </p>

                    </td>
                </tr>
            </table>

            <p>
                <input
                    type="submit"
                    name="save_urls"
                    class="button button-primary"
                    value="Save Settings"
                >
            </p>

        </form>
        <?php
        echo ob_get_clean();
    }
} 

new Bookmarker();