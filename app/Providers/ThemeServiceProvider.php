<?php

namespace App\Providers;

use Roots\Acorn\Sage\SageServiceProvider;
use App\Blocks\ExampleBlock;

class ThemeServiceProvider extends SageServiceProvider
{
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		parent::register();
	}

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		parent::boot();


		// USATAWIENIA MOTYWU
		add_action('acf/init', function () {
			if (function_exists('acf_add_options_page')) {
				acf_add_options_page([
					'page_title' => 'Ustawienia motywu',
					'menu_title' => 'Ustawienia motywu',
					'menu_slug'  => 'theme-settings',
					'capability' => 'edit_posts',
					'redirect'   => false,
				]);

				acf_add_options_page([
					'page_title' => 'Wezwanie do działania',
					'menu_title' => 'Wezwanie do działania',
					'menu_slug'  => 'bottom',
					'capability' => 'edit_posts',
					'redirect'   => false,
				]);

				acf_add_options_page([
					'page_title' => 'Gabinety - Stopka',
					'menu_title' => 'Gabinety - Stopka',
					'menu_slug'  => 'places-footer',
					'capability' => 'edit_posts',
					'redirect'   => false,
				]);

				acf_add_options_page([
					'page_title' => 'Ustawienia Megamenu',
					'menu_title' => 'Megamenu',
					'menu_slug'  => 'megamenu-settings',
					'capability' => 'edit_posts',
					'parent_slug' => 'themes.php', // Umieści stronę opcji pod "Wygląd"
					'position'   => 2,
					'icon_url'   => 'dashicons-menu-alt3',
					'redirect'   => false,
				]);

				/* 	acf_add_options_page([
					'page_title' => 'Oferta',
					'menu_title' => 'Oferta',
					'menu_slug'  => 'sectors',
					'capability' => 'edit_posts',
					'parent_slug' => '',
					'redirect'   => false,
				]); */
			}
		});
	}
}
