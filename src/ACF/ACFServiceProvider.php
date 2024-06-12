<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\ACF;

use Illuminate\Support\ServiceProvider;

class ACFServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        /**
         * Add capability check perhaps?
         */
        if (! is_plugin_active('advanced-custom-fields-pro/acf.php')) {
            add_action('admin_notices', function () {
                echo '<div class="notice notice-error"><p>The required plugin for the Config Expander package <strong>Advanced Custom Fields PRO</strong> is not active. Please install and activate it.</p></div>';
            });

            return;
        }

        add_action('admin_enqueue_scripts', [$this, 'registerPluginIcon'], 10, 0);
        add_action('acf/init', [$this, 'init'], 10, 0);
        add_action('acf/include_fields', [$this, 'addLocalFieldGroup'], 10, 0);
    }

    /**
     * Register plugin/package icon css which is used in the admin menu.
     */
    public function registerPluginIcon(): void
    {
        // Make sure the style is registered once.
        if (wp_style_is('yard_y_plugin_icon', 'registered')) {
            return;
        }

        wp_register_style('yard_y_plugin_icon', $this->route('/yard/config-expander/resources/styles/yard-y-icon'));
        wp_enqueue_style('yard_y_plugin_icon');
    }

    public function route(string $path): string
    {
        return config('app.url') . $path;
    }

    public function init(): void
    {
        $this->initSettingsPage();
    }

    public function initSettingsPage(): void
    {
        $current_user = \wp_get_current_user();

        if (! in_array('administrator', $current_user->roles)) {
            return;
        }

        if (! function_exists('acf_add_options_page')) {
            return;
        }

        \acf_add_options_page([
            'page_title' => __('Yard Config+'),
            'icon_url' => 'dashicons-yard-y',
            'position' => 66, // Investigate this position a bit more.
        ]);
    }

    public function addLocalFieldGroup()
    {
        if (! function_exists('acf_add_local_field_group')) {
            return;
        }

        acf_add_local_field_group([
            'key' => 'group_666845f092657',
            'title' => 'Instellingen',
            'fields' => [
                [
                    'key' => 'field_666962b381791',
                    'label' => 'Site bescherming',
                    'name' => '',
                    'aria-label' => '',
                    'type' => 'tab',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => [
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ],
                    'placement' => 'top',
                    'endpoint' => 0,
                    'selected' => 0,
                ],
                [
                    'key' => 'field_666962bd81792',
                    'label' => 'Type becherming',
                    'name' => 'type_protection_website',
                    'aria-label' => '',
                    'type' => 'select',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => [
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ],
                    'choices' => [
                        'site' => 'Site',
                        'login' => 'Login',
                        'both' => 'Beiden',
                    ],
                    'default_value' => false,
                    'return_format' => 'value',
                    'multiple' => 0,
                    'allow_null' => 0,
                    'ui' => 0,
                    'ajax' => 0,
                    'placeholder' => '',
                ],
                [
                    'key' => 'field_666962a881790',
                    'label' => 'Whitelisting',
                    'name' => '',
                    'aria-label' => '',
                    'type' => 'tab',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => [
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ],
                    'placement' => 'top',
                    'endpoint' => 0,
                    'selected' => 0,
                ],
                [
                    'key' => 'field_666845f07125a',
                    'label' => 'IP Whistelisting',
                    'name' => 'ip_whistelisting',
                    'aria-label' => '',
                    'type' => 'group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => [
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ],
                    'layout' => 'block',
                    'sub_fields' => [
                        [
                            'key' => 'field_666846607125c',
                            'label' => '',
                            'name' => 'ip_whistelisting_group',
                            'aria-label' => '',
                            'type' => 'repeater',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => [
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ],
                            'layout' => 'table',
                            'pagination' => 0,
                            'min' => 0,
                            'max' => 0,
                            'collapsed' => '',
                            'button_label' => 'Nieuwe regel',
                            'rows_per_page' => 20,
                            'sub_fields' => [
                                [
                                    'key' => 'field_666846787125d',
                                    'label' => 'IP-adres',
                                    'name' => 'whitelisted_ip_address',
                                    'aria-label' => '',
                                    'type' => 'text',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => [
                                        'width' => '',
                                        'class' => '',
                                        'id' => '',
                                    ],
                                    'default_value' => '',
                                    'maxlength' => '',
                                    'placeholder' => '',
                                    'prepend' => '',
                                    'append' => '',
                                    'parent_repeater' => 'field_666846607125c',
                                ],
                                [
                                    'key' => 'field_66695f40041d3',
                                    'label' => 'Omschrijving',
                                    'name' => 'whitelisted_description',
                                    'aria-label' => '',
                                    'type' => 'text',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => [
                                        'width' => '',
                                        'class' => '',
                                        'id' => '',
                                    ],
                                    'default_value' => '',
                                    'maxlength' => '',
                                    'placeholder' => '',
                                    'prepend' => '',
                                    'append' => '',
                                    'parent_repeater' => 'field_666846607125c',
                                ],
                                [
                                    'key' => 'field_66695f66041d4',
                                    'label' => 'Type',
                                    'name' => 'type',
                                    'aria-label' => '',
                                    'type' => 'select',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => [
                                        'width' => '',
                                        'class' => '',
                                        'id' => '',
                                    ],
                                    'choices' => [
                                        'site' => 'Site',
                                        'login' => 'Login',
                                        'both' => 'Beiden',
                                    ],
                                    'default_value' => false,
                                    'return_format' => 'value',
                                    'multiple' => 0,
                                    'allow_null' => 0,
                                    'ui' => 0,
                                    'ajax' => 0,
                                    'placeholder' => '',
                                    'parent_repeater' => 'field_666846607125c',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'location' => [
                [
                    [
                        'param' => 'options_page',
                        'operator' => '==',
                        'value' => 'acf-options-yard-config',
                    ],
                ],
            ],
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
            'show_in_rest' => 0,
        ]);
    }
}
