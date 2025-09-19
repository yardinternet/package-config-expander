<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\LoginScreenCustomization;

use Yard\ConfigExpander\Support\Traits\Route;

class LoginScreenCustomization
{
	use Route;

	protected string $resourcePath;

	public function __construct(string $resourcePath)
	{
		$this->resourcePath = $resourcePath;
	}

	/**
	 * Customise styling of the login page.
	 */
	public function loginPageStyle(): void
	{
		$source = apply_filters('yard::config-expander/login/style-file', $this->route('/yard/config-expander/resources/css/login-style.css'));
		wp_enqueue_style('config-expander-login-styling', $source, [], false, 'all');
	}

	/**
	 * Customize the login logo.
	 */
	public function loginLogo(): void
	{
		$logo = $this->route('/yard/config-expander/resources/images/logo-yard-black.svg');

		if (file_exists(sprintf('%s/images/company-logo.png', $this->resourcePath))) {
			$logo = sprintf('%s/resources/images/%s', get_stylesheet_directory_uri(), 'company-logo.png');
		}

		$logo = (string) apply_filters('yard::config-expander/login/logo', $logo);

		$css = '<style type="text/css">#login h1 a {
            height: 110px !important;
            background-image: url(' . $logo . ') !important;
        }</style>';

		echo apply_filters('yard::config-expander/login/css', $css);
	}

	/**
	 * Customize the url of the anchor around the logo.
	 */
	public function loginLogoUrl(): string
	{
		return apply_filters('yard::config-expander/login/logo-url', get_site_url());
	}

	/**
	 * Title of the link
	 */
	public function loginLogoTitle(): string
	{
		return apply_filters('yard::config-expander/login/logo-name', get_bloginfo('name'));
	}
}
