<?php
namespace EssentialAddonsElementor\Traits;

if (!defined('ABSPATH')) {
    exit();
}
// Exit if accessed directly

use EssentialAddonsElementor\Classes\WPDeveloper_Notice;

trait Admin
{
    protected $is_pro = false;

    /**
     * Create an admin menu.
     * @param
     * @return void
     * @since 1.1.2
     */
    public function admin_menu()
    {
        add_submenu_page(
            'elementor',
            'Essential Addons',
            'Essential Addons',
            'manage_options',
            'eael-settings',
            array($this, 'eael_admin_settings_page')
        );
    }

    /**
     * Loading all essential scripts
     * @param
     * @return void
     * @since 1.1.2
     */
    public function admin_enqueue_scripts($hook)
    {
        wp_enqueue_style('essential_addons_elementor-notice-css', $this->plugin_url . '/assets/admin/css/eael-notice.css', false, $this->plugin_version);

        if (isset($hook) && $hook == 'plugins.php') {
            wp_enqueue_style('sweetalert2-css', $this->plugin_url . '/assets/admin/vendor/sweetalert2/css/sweetalert2.min.css', false, $this->plugin_version);
            wp_enqueue_script('sweetalert2-js', $this->plugin_url . '/assets/admin/vendor/sweetalert2/js/sweetalert2.min.js', array('jquery', 'essential_addons_core-js'), $this->plugin_version, true);
            wp_enqueue_script('sweetalert2-core-js', $this->plugin_url . '/assets/admin/vendor/sweetalert2/js/core.js', array('jquery'), $this->plugin_version, true);
        } // check this

        if (isset($hook) && $hook == 'elementor_page_eael-settings') {
            wp_enqueue_style('essential_addons_elementor-admin-css', $this->plugin_url . '/assets/admin/css/admin.css', false, $this->plugin_version);
            wp_enqueue_style('sweetalert2-css', $this->plugin_url . '/assets/admin/vendor/sweetalert2/css/sweetalert2.min.css', false, $this->plugin_version);
            wp_enqueue_script('sweetalert2-js', $this->plugin_url . '/assets/admin/vendor/sweetalert2/js/sweetalert2.min.js', array('jquery', 'essential_addons_core-js'), $this->plugin_version, true);
            wp_enqueue_script('sweetalert2-core-js', $this->plugin_url . '/assets/admin/vendor/sweetalert2/js/core.js', array('jquery'), $this->plugin_version, true);
            wp_enqueue_script('essential_addons_elementor-admin-js', $this->plugin_url . '/assets/admin/js/admin.js', array('jquery'), $this->plugin_version, true);

            wp_localize_script('essential_addons_elementor-admin-js', 'localize', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('essential-addons-elementor'),
            ));
        }
    }

    /**
     * Create settings page.
     * @param
     * @return void
     * @since 1.1.2
     */
    public function eael_admin_settings_page()
    {
        echo '<div class="eael-settings-wrap">
		  	<form action="" method="POST" id="eael-settings" name="eael-settings">
		  		<div class="eael-header-bar">
					<div class="eael-header-left">
						<div class="eael-admin-logo-inline">
							<img src="' . $this->plugin_url . '/assets/admin/images/ea-logo.svg' . '">
						</div>
						<h2 class="title">' . __('Essential Addons Settings', 'essential-addons-elementor') . '</h2>
					</div>
					<div class="eael-header-right">
					<button type="submit" class="button eael-btn js-eael-settings-save">' . __('Save settings', 'essential-addons-elementor') . '</button>
					</div>
				</div>
			  	<div class="eael-settings-tabs">
			    	<ul class="eael-tabs">
				      	<li><a href="#general" class="active"><img src="' . $this->plugin_url . '/assets/admin/images/icon-settings.svg' . '"><span>General</span></a></li>
				      	<li><a href="#elements"><img src="' . $this->plugin_url . '/assets/admin/images/icon-modules.svg' . '"><span>Elements</span></a></li>
						<li><a href="#extensions"><img src="' . $this->plugin_url . '/assets/admin/images/icon-extensions.svg' . '"><span>Extensions</span></a></li>
						<li><a href="#go-pro"><img src="' . $this->plugin_url . '/assets/admin/images/icon-upgrade.svg' . '"><span>Go Premium</span></a></li>
                    </ul>';
        include_once $this->plugin_path . 'includes/templates/admin/general.php';
        include_once $this->plugin_path . 'includes/templates/admin/elements.php';
        include_once $this->plugin_path . 'includes/templates/admin/extensions.php';
        include_once $this->plugin_path . 'includes/templates/admin/go-pro.php';
        echo '</div>
            </form>
        </div>';
    }

    public function admin_notice()
    {
        $notice = new WPDeveloper_Notice($this->plugin_basename, $this->plugin_version);
        $scheme = (parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY)) ? '&' : '?';
        $url = $_SERVER['REQUEST_URI'] . $scheme;
        $notice->links = [
            'review' => array(
                'later' => array(
                    'link' => 'https://wpdeveloper.net/review-essential-addons-elementor',
                    'target' => '_blank',
                    'label' => __('Ok, you deserve it!', 'essential-addons-elementor'),
                    'icon_class' => 'dashicons dashicons-external',
                ),
                'allready' => array(
                    'link' => $url,
                    'label' => __('I already did', 'essential-addons-elementor'),
                    'icon_class' => 'dashicons dashicons-smiley',
                    'data_args' => [
                        'dismiss' => true,
                    ],
                ),
                'maybe_later' => array(
                    'link' => $url,
                    'label' => __('Maybe Later', 'essential-addons-elementor'),
                    'icon_class' => 'dashicons dashicons-calendar-alt',
                    'data_args' => [
                        'later' => true,
                    ],
                ),
                'support' => array(
                    'link' => 'https://wpdeveloper.net/support',
                    'label' => __('I need help', 'essential-addons-elementor'),
                    'icon_class' => 'dashicons dashicons-sos',
                ),
                'never_show_again' => array(
                    'link' => $url,
                    'label' => __('Never show again', 'essential-addons-elementor'),
                    'icon_class' => 'dashicons dashicons-dismiss',
                    'data_args' => [
                        'dismiss' => true,
                    ],
                ),
            ),
        ];

        /**
         * This is review message and thumbnail.
         */
        $notice->message('review', '<p>' . __('We hope you\'re enjoying Essential Addons for Elementor! Could you please do us a BIG favor and give it a 5-star rating on WordPress to help us spread the word and boost our motivation?', 'essential-addons-elementor') . '</p>');
        $notice->thumbnail('review', plugins_url('admin/assets/images/ea-logo.svg', ESSENTIAL_ADDONS_BASENAME));

        /**
         * Current Notice End Time.
         * Notice will dismiss in 3 days if user does nothing.
         */
        $notice->cne_time = '3 Day';
        /**
         * Current Notice Maybe Later Time.
         * Notice will show again in 7 days
         */
        $notice->maybe_later_time = '7 Day';

        $notice->text_domain = 'essential-addons-elementor';

        $notice->options_args = array(
            'notice_will_show' => [
                'opt_in' => $notice->timestamp,
                'review' => $notice->makeTime($notice->timestamp, '4 Day'), // after 4 days
            ],
        );

        $notice->init();
    }
}
