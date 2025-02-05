<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\ACF;

use Illuminate\Support\ServiceProvider;
use Yard\ConfigExpander\Traits\PluginActive;
use Yard\ConfigExpander\Traits\Route;

class ACFServiceProvider extends ServiceProvider
{
	use PluginActive;
	use Route;

	public function boot(): void
	{
		if (! $this->isPluginActive('advanced-custom-fields-pro/acf.php')) {
			add_action('admin_notices', function () {
				// @phpstan-ignore-next-line
				echo sprintf('<div class="notice notice-error"><p><strong>Yard Digital: </strong>%s</p></div>', __('The required plugin for the Config Expander package <strong>Advanced Custom Fields PRO</strong> is not active. Please install and activate it.', 'config-expander'));
			});

			return;
		}

		add_action('acf/init', [$this, 'init'], 10, 0);
		add_action('acf/include_fields', [$this, 'addLocalFieldGroup'], 10, 0);
		add_action('acf/save_post', [$this, 'doActionOptionsUpdated'], 20);
	}

	public function init(): void
	{
		$this->initSettingsPage();
	}

	public function initSettingsPage(): void
	{
		$current_user = wp_get_current_user();

		if (! in_array('administrator', $current_user->roles)) {
			return;
		}

		if (! function_exists('acf_add_options_page')) {
			return;
		}

		acf_add_options_page([
			'menu_slug' => 'acf-options-yard-config',
			'page_title' => 'Yard Config+',
			'parent_slug' => 'options-general.php',
		]);
	}

	/**
	 * Call action when config options are updated.
	 */
	public function doActionOptionsUpdated(): void
	{
		$screen = get_current_screen();

		if (! empty($screen->id) && str_contains($screen->id, 'acf-options-yard-config')) {
			do_action('yard::config-expander/acf/settings-updated');
		}
	}

	public function addLocalFieldGroup(): void
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
					'label' => 'Site afscherming',
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
					'label' => 'Type afscherming',
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
						'none' => 'Geen',
						'site' => 'Frontend',
						'login' => 'Admin',
						'both' => 'Beide (Frontend & Admin)',
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
					'key' => 'field_666845f07125a',
					'label' => 'IP-adres whitelist',
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
							'button_label' => 'Voeg IP-adres toe',
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
									'label' => 'Type afscherming',
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
										'site' => 'Frontend',
										'login' => 'Admin',
										'both' => 'Beide (Frontend & Admin)',
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
			'label_placement' => 'left',
			'instruction_placement' => 'field',
			'hide_on_screen' => '',
			'active' => true,
			'description' => '',
			'show_in_rest' => 0,
		]);
	}
}
