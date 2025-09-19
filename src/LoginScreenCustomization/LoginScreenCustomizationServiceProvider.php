<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\LoginScreenCustomization;

use Illuminate\Support\ServiceProvider;

class LoginScreenCustomizationServiceProvider extends ServiceProvider
{
	public function boot(): void
	{
		add_action('wp_loaded', $this->loginScreenCustomization(...), 10, 1);
	}

	/**
	 * Register the necessary hooks for login page customization.
	 *
	 * @see https://codex.wordpress.org/Customizing_the_Login_Form
	 */
	public function loginScreenCustomization(): void
	{
		$loginScreenCustomization = new LoginScreenCustomization($this->app->resourcePath());

		add_action('login_enqueue_scripts', $loginScreenCustomization->loginPageStyle(...));
		add_action('login_head', $loginScreenCustomization->loginLogo(...));
		add_filter('login_headerurl', $loginScreenCustomization->loginLogoUrl(...));
		add_filter('login_headertext', $loginScreenCustomization->loginLogoTitle(...));
	}
}
