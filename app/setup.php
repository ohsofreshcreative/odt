<?php

/**
 * Theme setup.
 */

namespace App;

use Illuminate\Support\Facades\Vite;

/**
 * Inject styles into the block editor.
 *
 * @return array
 */
add_filter('block_editor_settings_all', function ($settings) {
	$style = Vite::asset('resources/css/editor.css');

	$settings['styles'][] = [
		'css' => "@import url('{$style}')",
	];

	return $settings;
});

/**
 * Inject scripts into the block editor.
 *
 * @return void
 */
add_filter('admin_head', function () {
	if (! get_current_screen()?->is_block_editor()) {
		return;
	}

	$dependencies = json_decode(Vite::content('editor.deps.json'));

	foreach ($dependencies as $dependency) {
		if (! wp_script_is($dependency)) {
			wp_enqueue_script($dependency);
		}
	}

	echo Vite::withEntryPoints([
		'resources/js/editor.js',
	])->toHtml();
});

/**
 * Use the generated theme.json file.
 *
 * @return string
 */
add_filter('theme_file_path', function ($path, $file) {
	return $file === 'theme.json'
		? public_path('build/assets/theme.json')
		: $path;
}, 10, 2);

/**
 * Register the initial theme setup.
 *
 * @return void
 */

add_action('after_setup_theme', function () {

	// Dodaj wsparcie dla WooCommerce
	add_theme_support('woocommerce');
	add_theme_support('wc-product-gallery-zoom');
	add_theme_support('wc-product-gallery-lightbox');
	add_theme_support('wc-product-gallery-slider');


	/**
	 * Disable full-site editing support.
	 *
	 * @link https://wptavern.com/gutenberg-10-5-embeds-pdfs-adds-verse-block-color-options-and-introduces-new-patterns
	 */
	remove_theme_support('block-templates');

	/**
	 * Register the navigation menus.
	 *
	 * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
	 */
	register_nav_menus([
		'primary_navigation' => __('Primary Navigation', 'sage'),
	]);

	/**
	 * Disable the default block patterns.
	 *
	 * @link https://developer.wordpress.org/block-editor/developers/themes/theme-support/#disabling-the-default-block-patterns
	 */
	remove_theme_support('core-block-patterns');

	/**
	 * Enable plugins to manage the document title.
	 *
	 * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
	 */
	add_theme_support('title-tag');

	/**
	 * Enable post thumbnail support.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support('post-thumbnails');

	/**
	 * Enable responsive embed support.
	 *
	 * @link https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#responsive-embedded-content
	 */
	add_theme_support('responsive-embeds');

	/**
	 * Enable HTML5 markup support.
	 *
	 * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
	 */
	add_theme_support('html5', [
		'caption',
		'comment-form',
		'comment-list',
		'gallery',
		'search-form',
		'script',
		'style',
	]);

	/**
	 * Enable selective refresh for widgets in customizer.
	 *
	 * @link https://developer.wordpress.org/reference/functions/add_theme_support/#customize-selective-refresh-widgets
	 */
	add_theme_support('customize-selective-refresh-widgets');
}, 20);

/*--- WOOCOMMERCE PHP FILES ---*/

array_map(function ($file) {
	require_once $file;
}, array_merge(
	glob(get_theme_file_path('app/Woo/*.php')) ?: [],
	glob(get_theme_file_path('app/Woo/*/*.php')) ?: []
));


/*--- WOOCOMMERCE SIDEBAR ---*/

add_action('widgets_init', function () {
	register_sidebar([
		'name'          => __('Sklep - Filtry', 'sage'),
		'id'            => 'sidebar-shop',
		'before_widget' => '<section class="widget %1$s %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<p class="font-header text-h5 widget-title font-bold mb-4">',
		'after_title'   => '</p>',
	]);
});

/**
 * Register the theme sidebars.
 *
 * @return void
 */
add_action('widgets_init', function () {
	$defaultConfig = [
		'before_widget' => '<section class="footer_widget widget %1$s %2$s">',
		'after_widget' => '</section>',
		'before_title' => '<p class="font-header text-h5 widget-title primary mb-4 flex">',
		'after_title' => '</p>',
	];

	register_sidebar([
		'name' => __('Primary', 'sage'),
		'id' => 'sidebar-primary',
	] + $defaultConfig);

	register_sidebar([
		'name' => __('Footer 1', 'sage'),
		'id'   => 'sidebar-footer-1',
	] + $defaultConfig);

	register_sidebar([
		'name' => __('Footer 2', 'sage'),
		'id'   => 'sidebar-footer-2',
	] + $defaultConfig);

	register_sidebar([
		'name' => __('Footer 3', 'sage'),
		'id'   => 'sidebar-footer-3',
	] + $defaultConfig);

	register_sidebar([
		'name' => __('Footer 4', 'sage'),
		'id'   => 'sidebar-footer-4',
	] + $defaultConfig);
});

/*--- DISABLE COMMENTS ---*/

add_action('init', function () {
	remove_post_type_support('post', 'comments');
	remove_post_type_support('page', 'comments');

	add_filter('comments_open', '__return_false', 20, 2);
	add_filter('pings_open', '__return_false', 20, 2);

	add_filter('comments_array', '__return_empty_array', 10, 2);
});

add_action('admin_init', function () {
	remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
});

add_action('admin_menu', function () {
	remove_menu_page('edit-comments.php');
});

add_action('wp_before_admin_bar_render', function () {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu('comments');
});

/*--- CATEGORY IMAGE ---*/

/**
 * Register the ACF fields for Category taxonomy.
 */
add_action('acf/init', function () {
	if (function_exists('acf_add_local_field_group')) {
		acf_add_local_field_group([
			'key' => 'group_category_settings',
			'title' => 'Ustawienia Kategorii',
			'fields' => [
				[
					'key' => 'field_category_header',
					'label' => 'Nagłówek',
					'name' => 'category_header',
					'type' => 'text',
					'instructions' => 'Opcjonalny nagłówek, który może zastąpić domyślną nazwę kategorii.',
				],
				[
					'key' => 'field_category_description',
					'label' => 'Opis',
					'name' => 'category_description',
					'type' => 'textarea',
					'instructions' => 'Krótki opis wyświetlany na stronie kategorii.',
				],
				[
					'key' => 'field_category_image',
					'label' => 'Zdjęcie Kategorii',
					'name' => 'category_image',
					'type' => 'image',
					'instructions' => 'Dodaj obrazek, który będzie wyświetlany jako tło lub nagłówek dla tej kategorii.',
					'return_format' => 'array', // Zwraca tablicę z danymi obrazka (url, alt, etc.)
					'preview_size' => 'medium',
					'library' => 'all',
				],
			],
			'location' => [
				[
					[
						'param' => 'taxonomy',
						'operator' => '==',
						'value' => 'category', // Celujemy w taksonomię "category"
					],
				],
			],
			'menu_order' => 0,
			'position' => 'normal',
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'active' => true,
		]);
	}
});

/**
 * Remove archive title prefix (e.g., "Category:", "Tag:").
 */
add_filter('get_the_archive_title', function ($title) {
	if (is_category()) {
		$title = single_cat_title('', false);
	} elseif (is_tag()) {
		$title = single_tag_title('', false);
	} elseif (is_author()) {
		$title = '<span class="vcard">' . get_the_author() . '</span>';
	} elseif (is_post_type_archive()) {
		$title = post_type_archive_title('', false);
	} elseif (is_tax()) {
		$title = single_term_title('', false);
	}

	return $title;
});

/*--- GSAP ---*/

add_action('wp_enqueue_scripts', function () {
	/**
	 * Rejestracja i ładowanie skryptów.
	 */

	// Ładuj GSAP i ScrollTrigger z CDN.
	// Trzeci argument (tablica []) oznacza brak zależności.
	// Piąty argument (true) umieszcza skrypty w stopce.
	wp_enqueue_script('gsap-cdn', 'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js', [], null, true);

	// Ustawiamy zależność 'gsap-st-cdn' od 'gsap-cdn', aby załadowały się w dobrej kolejności.
	wp_enqueue_script('gsap-st-cdn', 'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js', ['gsap-cdn'], null, true);
}, 1); // Ustawiamy priorytet na 1, aby wykonało się BARDZO wcześnie.


add_action('after_setup_theme', function () {
	remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
	remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
});




/**
 * Dynamically generate checkboxes for subsidies in Contact Form 7.
 */
add_action('after_setup_theme', function () {
	remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
	remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
});


/**
 * Register custom form tag for CF7 to display subsidies.
 */
add_action('wpcf7_init', function () {
	wpcf7_add_form_tag('subsidy_checkboxes', 'App\\custom_subsidy_checkboxes_handler');
});

/**
 * Handler for the [subsidy_checkboxes] form tag.
 *
 * @param WPCF7_FormTag $tag
 * @return string
 */
function custom_subsidy_checkboxes_handler($tag)
{
	$subsidies = get_field('subsidies', 'option');
	$output = '';

	if ($subsidies) {
		$output .= '<h2 class="text-2xl font-bold mb-4">Dofinansowania</h2>';
		$output .= '<div class="flex flex-col gap-2">';
		foreach ($subsidies as $subsidy) {
			if (!empty($subsidy['subsidy_name'])) {
				$name = esc_attr($subsidy['subsidy_name']);
				$output .= sprintf(
					'<label class="flex items-center gap-2"><input type="checkbox" name="subsidies[]" value="%s" /> <span>%s</span></label>',
					$name,
					esc_html($name)
				);
			}
		}
		$output .= '</div>';
	}

	return $output;
}


/**
 * Disable Contact Form 7 auto <p> tags.
 */
add_filter('wpcf7_autop_or_not', '__return_false');

/*--- WYSIWYG HEIGHT FIX ---*/

add_action('admin_footer', function () {
	$screen = function_exists('get_current_screen') ? get_current_screen() : null;
	if (!$screen || $screen->base !== 'post') return;
?>
	<script>
		(function() {
			const TARGET_HEIGHT = 140; // startowa wysokość

			function applyInitialHeight() {
				document.querySelectorAll('.acf-editor-wrap iframe[id^="acf-editor-"]').forEach((iframe) => {
					// Jeśli już ustawiliśmy startową wysokość, nie ruszaj więcej (żeby ręczny resize działał)
					if (iframe.dataset.acfInitialHeightApplied === '1') return;

					const current = parseInt(iframe.style.height || 0, 10);

					// Ustaw tylko jeśli jest puste albo większe od targetu (czyli "za wysokie")
					if (!current || current > TARGET_HEIGHT) {
						iframe.style.height = TARGET_HEIGHT + 'px';
					}

					iframe.dataset.acfInitialHeightApplied = '1';
				});
			}

			// Spróbuj kilka razy po załadowaniu (ACF/TinyMCE potrafią wstać później)
			let tries = 0;
			const timer = setInterval(() => {
				applyInitialHeight();
				tries++;
				if (tries >= 40) clearInterval(timer); // ~10s
			}, 250);

			// Obserwuj DOM tylko po to, żeby łapać NOWE edytory (np. po dodaniu bloku),
			// ale nie resetować tych, które użytkownik już rozciągnął.
			const obs = new MutationObserver(() => applyInitialHeight());
			obs.observe(document.body, {
				childList: true,
				subtree: true
			});

			window.addEventListener('load', () => setTimeout(applyInitialHeight, 500));
		})();
	</script>
<?php
});




add_action('template_redirect', function () {
	// Sprawdź, czy jesteśmy na stronie archiwum JAKIEJKOLWIEK taksonomii
	if (!is_tax() && !is_category() && !is_tag()) {
		return; // Jeśli nie, zakończ działanie
	}

	// Pobierz obiekt aktualnej taksonomii (kategorii, tagu itp.)
	$term = get_queried_object();

	// Upewnij się, że obiekt istnieje i jest terminem taksonomii
	if ($term instanceof \WP_Term) {
		// Pobierz wartość pola 'linked_page' dla tego konkretnego terminu
		$redirect_url = get_field('linked_page', $term);

		// Jeśli wybrano stronę, przekieruj
		if ($redirect_url) {
			wp_safe_redirect($redirect_url, 301);
			exit;
		}
	}
});

// CUSTOM POST TYPE BRANŻE
add_action('init', function () {
	// 1. Rejestracja taksonomii dla Oferty
	register_taxonomy('offer_category', ['offer'], [
		'label' => 'Kategorie Oferty',
		'public' => true,
		'hierarchical' => true,
		'show_in_rest' => true,
		'rewrite' => [
			'slug' => 'kategoria-oferty',
			'with_front' => false,
		],
	]);

	register_post_type('offer', [
		'label' => 'Oferta',
		'public' => true,
		'has_archive' => true,
		'rewrite' => [
			'slug' => 'oferta',
			'with_front' => false,
		],
		'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
		'show_in_rest' => true,
		'taxonomies' => ['offer_category'],
		'menu_icon' => 'dashicons-list-view',
	]);
});

// CUSTOM POST TYPE POMAGAMY W z własną taksonomią
add_action('init', function () {
	// 1. Rejestracja taksonomii dla "Pomagamy w"
	register_taxonomy('help_category', ['help'], [
		'label' => 'Kategorie specjalizacji',
		'public' => true,
		'hierarchical' => true,
		'show_in_rest' => true,
		'rewrite' => [
			'slug' => 'specjalizacje',
			'with_front' => false,
		],
	]);

	register_post_type('help', [
		'label' => 'Pomagamy w',
		'public' => true,
		'has_archive' => false,
		'rewrite' => [
			'slug' => 'specjalizacja',
			'with_front' => false,
		],
		'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
		'show_in_rest' => true,
		'taxonomies' => ['help_category'],
		'menu_icon' => 'dashicons-open-folder',
	]);
});

// CUSTOM POST TYPE Oferty pracy z własną taksonomią
add_action('init', function () {
	// 1. Rejestracja taksonomii dla Ofert Pracy
	register_taxonomy('job_category', ['jobs'], [
		'label' => 'Kategorie Ofert Pracy',
		'public' => true,
		'hierarchical' => true,
		'show_in_rest' => true,
		'rewrite' => ['slug' => 'praca-kategoria'],
	]);

	// 2. Rejestracja CPT i przypisanie nowej taksonomii
	register_post_type('jobs', [
		'label' => 'Oferty pracy',
		'public' => true,
		'has_archive' => false,
		'rewrite' => ['slug' => 'praca'],
		'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
		'show_in_rest' => true,
		'taxonomies' => ['job_category'],
		'menu_icon' => 'dashicons-open-folder',
	]);
});

add_action('init', function () {
	// 1. NAJPIERW REJESTRUJEMY NOWĄ, NIESTANDARDOWĄ TAKSONOMIĘ
	register_taxonomy('team_category', ['team'], [
		'label' => 'Kategorie Zespołu', // Nazwa ogólna
		'labels' => [
			'name'              => 'Kategorie Zespołu',
			'singular_name'     => 'Kategoria Zespołu',
			'search_items'      => 'Szukaj w kategoriach',
			'all_items'         => 'Wszystkie kategorie',
			'parent_item'       => 'Kategoria nadrzędna',
			'parent_item_colon' => 'Kategoria nadrzędna:',
			'edit_item'         => 'Edytuj kategorię',
			'update_item'       => 'Aktualizuj kategorię',
			'add_new_item'      => 'Dodaj nową kategorię',
			'new_item_name'     => 'Nazwa nowej kategorii',
			'menu_name'         => 'Kategorie',
		],
		'public'            => true,
		'hierarchical'      => true, // Ustawiamy na true, aby działały jak kategorie (drzewko), a nie tagi
		'show_ui'           => true,
		'show_in_menu'      => true, // Pokaże się jako podmenu pod "Zespół"
		'show_admin_column' => true,
		'query_var'         => true,
		'show_in_rest'      => true, // Ważne dla edytora blokowego
		'rewrite'           => ['slug' => 'zespol-kategoria'], // Slug dla archiwów tej taksonomii
	]);

	// 2. REJESTRUJEMY CUSTOM POST TYPE I PRZYPISUJEMY DO NIEGO NOWĄ TAKSONOMIĘ
	register_post_type('team', [
		'label' => 'Zespół',
		'public' => true,
		'has_archive' => 'zespol',
		'rewrite' => ['slug' => 'zespol', 'with_front' => false],
		'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
		'show_in_rest' => true,
		'taxonomies' => ['team_category'],
		'menu_icon' => 'dashicons-admin-users',
	]);
});


/**
 * Dodaje pola ACF 'Miejsce' i 'Wymiar czasowy' dla CPT 'jobs'.
 * Pola pojawią się w prawej kolumnie edytora Gutenberg.
 */
add_action('acf/init', function () {
	if (function_exists('acf_add_local_field_group')) {
		acf_add_local_field_group([
			'key' => 'group_job_details_sidebar',
			'title' => 'Szczegóły Oferty',
			'fields' => [
				[
					'key' => 'field_job_location',
					'label' => 'Miejsce pracy',
					'name' => 'job_location',
					'type' => 'text',
				],
				[
					'key' => 'field_job_time_dimension',
					'label' => 'Wymiar czasowy',
					'name' => 'job_time_dimension',
					'type' => 'text',
				],
			],
			'location' => [
				[
					[
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'jobs',
					],
				],
			],
			'position' => 'side',
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'active' => true,
		]);
	}
});

/**
 * Dodaje nowe kolumny do listy ofert pracy w panelu admina.
 */
add_filter('manage_jobs_posts_columns', function ($columns) {
	$columns['job_location'] = 'Miejsce pracy';
	$columns['job_time_dimension'] = 'Wymiar czasowy';
	return $columns;
});

/**
 * Wypełnia nowe kolumny danymi z pól ACF.
 */
add_action('manage_jobs_posts_custom_column', function ($column, $post_id) {
	switch ($column) {
		case 'job_location':
			echo esc_html(get_field('job_location', $post_id));
			break;
		case 'job_time_dimension':
			// ZMIANA: Teraz po prostu wyświetlamy wartość pola tekstowego
			echo esc_html(get_field('job_time_dimension', $post_id));
			break;
	}
}, 10, 2);

/*--- EBOOK POST ---*/

add_filter('theme_offer_templates', function ($post_templates) {
	$post_templates['ebook-post'] = __('Ebook', 'sage');

	return $post_templates;
});

add_action('init', function () {
	add_post_type_support('offer', 'page-attributes');
});

/*--- MEGA MENU FIELDS ---*/

add_action('wp_nav_menu_item_custom_fields', function ($item_id, $item) {
	$image_src = get_post_meta($item_id, '_menu_item_image_src', true);
?>
	<p class="field-image-src description description-wide">
		<label for="edit-menu-item-image-src-<?php echo $item_id; ?>">
			<?php _e('Image URL', 'sage'); ?><br />
			<input type="text" id="edit-menu-item-image-src-<?php echo $item_id; ?>" class="widefat edit-menu-item-image-src" name="menu-item-image-src[<?php echo $item_id; ?>]" value="<?php echo esc_attr($image_src); ?>" />
		</label>
	</p>
<?php
}, 10, 2);

/**
 * Save custom fields for menu items.
 */
add_action('wp_update_nav_menu_item', function ($menu_id, $menu_item_db_id) {
	if (isset($_REQUEST['menu-item-image-src'][$menu_item_db_id])) {
		$image_src = $_REQUEST['menu-item-image-src'][$menu_item_db_id];
		update_post_meta($menu_item_db_id, '_menu_item_image_src', $image_src);
	}
}, 10, 2);

/*--- WOOCOMMERCE HIDE COUNTRY ---*/

add_filter('woocommerce_checkout_fields', function ($fields) {

	// Usuń pole kraju z sekcji billing
	if (isset($fields['billing']['billing_country'])) {
		unset($fields['billing']['billing_country']);
	}

	// Jeśli masz osobne pola wysyłki i też chcesz ukryć:
	if (isset($fields['shipping']['shipping_country'])) {
		unset($fields['shipping']['shipping_country']);
	}

	return $fields;
}, 20);

// Ustaw domyślny kraj na PL (billing + shipping)
add_filter('default_checkout_billing_country', fn() => 'PL');
add_filter('default_checkout_shipping_country', fn() => 'PL');

// Dla pewności – nawet jeśli ktoś spróbuje przesłać inne dane:
add_action('woocommerce_checkout_process', function () {
	$_POST['billing_country']  = 'PL';
	$_POST['shipping_country'] = 'PL';
});

/* add_action('wp_footer', function () {
	if (!is_cart()) return;
	echo '<pre style="background:#fff;padding:10px;max-width:1200px;overflow:auto;">';
	print_r(WC()->cart->get_cart());
	echo '</pre>';
});
 */

add_action('wp_enqueue_scripts', function () {
	if (function_exists('acf_enqueue_scripts')) {
		acf_enqueue_scripts();
	}
}, 20);

/*--- DISABLE RELATED ---*/

remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);



/*--- PRICE ABOVE ADD TO CART  ---*/

add_action('woocommerce_single_product_summary', function () {
	// Usuń cenę z jej domyślnej lokalizacji
	remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);

	// Dodaj cenę ponownie, ale po krótkim opisie (który ma priorytet 20)
	// a przed przyciskiem "Dodaj do koszyka" (który ma priorytet 30)
	add_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 25);
}, 1);

/*--- WOOCOMMERCE RELATED PRODUCTS CUSTOMIZATION ---*/

// 1. Wyświetlanie niestandardowego pola i przygotowanie danych dla JS
add_action('woocommerce_single_product_summary', function () {
	$product_id = 1141;
	if (get_the_ID() != $product_id) return;

	global $product;
	$base_price = $product->get_price();
	$addon_price = 145;

	// Dodajemy wrapper z atrybutami data-* dla naszego skryptu JS
	echo '<div 
            id="cliftonstrengths-dynamic-price-wrapper" 
            data-product-id="' . esc_attr($product_id) . '" 
            data-base-price="' . esc_attr($base_price) . '" 
            data-addon-price="' . esc_attr($addon_price) . '"
          >';

	echo '<div class="cliftonstrengths-field-wrapper" style="margin-bottom: 10px;">';
	echo '<label for="cliftonstrengths_persons_count" style="font-weight: bold; display: block; margin-top:24px; margin-bottom: 5px;">Liczba osób do testu CliftonStrengths</label>';
	echo '<input type="number" id="cliftonstrengths_persons_count" name="cliftonstrengths_persons_count" min="0" value="0" placeholder="Wpisz liczbę osób..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; background:#FFF;">';
	echo '<p style="font-size: 0.9em; color: #666; margin-top: 5px;">Koszt dodatkowy: <b>' . number_format_i18n($addon_price, 2) . ' ' . get_woocommerce_currency_symbol() . '</b> za osobę.</p>';
	echo '</div>';

	// Zamykamy wrapper po cenie, aby objąć nią działanie skryptu
	add_action('woocommerce_single_product_summary', function () {
		echo '</div>';
	}, 26);
}, 24); // Wyświetlamy pole tuż przed ceną (która jest na priorytecie 25)

// 2. Zapisanie wartości pola do danych koszyka (bez zmian)
add_filter('woocommerce_add_cart_item_data', function ($cart_item_data, $product_id, $variation_id) {
	if (isset($_POST['cliftonstrengths_persons_count']) && $product_id == 1141) {
		$persons_count = intval(sanitize_text_field($_POST['cliftonstrengths_persons_count']));
		if ($persons_count > 0) {
			$cart_item_data['cliftonstrengths_persons_count'] = $persons_count;
			$cart_item_data['unique_key'] = md5($product_id . $persons_count);
		}
	}
	return $cart_item_data;
}, 10, 3);

// 3. Dynamiczna zmiana ceny w koszyku (bez zmian)
add_action('woocommerce_before_calculate_totals', function ($cart) {
	if (is_admin() && ! defined('DOING_AJAX')) return;

	foreach ($cart->get_cart() as $cart_item) {
		if (isset($cart_item['cliftonstrengths_persons_count'])) {
			$persons_count = $cart_item['cliftonstrengths_persons_count'];
			$additional_cost_per_person = 145;
			// Pobieramy cenę bazową z produktu, a nie z koszyka, aby uniknąć wielokrotnego naliczania
			$_product = wc_get_product($cart_item['product_id']);
			$base_price = $_product->get_price();

			$new_price = $base_price + ($persons_count * $additional_cost_per_person);
			$cart_item['data']->set_price($new_price);
		}
	}
}, 20, 1);

// 4. Wyświetlanie informacji w koszyku i przy zamówieniu (bez zmian)
add_filter('woocommerce_get_item_data', function ($item_data, $cart_item) {
	if (isset($cart_item['cliftonstrengths_persons_count'])) {
		$persons_count = $cart_item['cliftonstrengths_persons_count'];
		$total_additional_cost = $persons_count * 145;

		$item_data[] = ['key' => 'Liczba osób (test)', 'value' => $persons_count];
		$item_data[] = ['key' => 'Dodatkowy koszt', 'value' => wc_price($total_additional_cost)];
	}
	return $item_data;
}, 10, 2);

// 5. Zapisanie danych do meta zamówienia (bez zmian)
add_action('woocommerce_checkout_create_order_line_item', function ($item, $cart_item_key, $values, $order) {
	if (isset($values['cliftonstrengths_persons_count'])) {
		$persons_count = $values['cliftonstrengths_persons_count'];
		$total_additional_cost = $persons_count * 145;

		$item->add_meta_data('Liczba osób (test)', $persons_count);
		$item->add_meta_data('Dodatkowy koszt (test)', wc_price($total_additional_cost));
	}
}, 10, 4);

// 6. Dodanie skryptu JS do dynamicznej aktualizacji ceny
add_action('wp_footer', function () {
	// Upewnij się, że jesteśmy na stronie produktu
	if (!is_product()) return;

	// Sprawdź, czy nasz wrapper istnieje na stronie
	if (get_the_ID() != 1141) return;
?>
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const wrapper = document.getElementById('cliftonstrengths-dynamic-price-wrapper');
			if (!wrapper) return;

			const input = document.getElementById('cliftonstrengths_persons_count');
			const priceElement = wrapper.querySelector('.price');

			if (!input || !priceElement) return;

			const basePrice = parseFloat(wrapper.dataset.basePrice);
			const addonPrice = parseFloat(wrapper.dataset.addonPrice);
			const currencySymbol = '<?php echo get_woocommerce_currency_symbol(); ?>';
			const currencyFormat = '<?php echo esc_js(get_woocommerce_price_format()); ?>';

			function formatPrice(price) {
				let formattedPrice = price.toFixed(2).replace('.', ',');
				return currencyFormat.replace('%1$s', '').replace('%2$s', formattedPrice + ' ' + currencySymbol).trim();
			}

			function updatePrice() {
				const persons = parseInt(input.value) || 0;
				const totalAddonCost = persons * addonPrice;
				const newTotal = basePrice + totalAddonCost;

				// Aktualizujemy HTML wewnątrz elementu ceny
				priceElement.innerHTML = '<span class="woocommerce-Price-amount amount"><bdi>' + formatPrice(newTotal) + '</bdi></span>';
			}

			input.addEventListener('input', updatePrice);
			input.addEventListener('change', updatePrice);

			// Ustaw cenę początkową
			updatePrice();
		});
	</script>
<?php
});

/*--- COMMING SOON ---*/


add_action('template_redirect', function () {
	// Sprawdzamy, czy jesteśmy na stronie produktu i czy ma ona ustawione pole "coming_soon".
	if (is_product() && get_post_meta(get_the_ID(), 'coming_soon', true)) {

		// Pobierz zarejestrowany wzorzec blokowy o slug 'woocommerce/coming-soon'
		$block_pattern = \WP_Block_Patterns_Registry::get_instance()->get_registered('woocommerce/coming-soon');

		if ($block_pattern) {
			// Wyświetl nagłówek strony
			get_header();

			// Przetwórz i wyświetl zawartość wzorca
			echo do_blocks($block_pattern['content']);

			// Wyświetl stopkę strony
			get_footer();
		} else {
			// Coś poszło nie tak - wzorzec nie został znaleziony
			echo "Błąd: Wzorzec 'coming-soon' nie został znaleziony.";
		}

		// Zatrzymujemy dalsze wykonywanie skryptu
		exit;
	}
});


/*--- DELETE CATEGORY FROM URL 

// Filter the category link to remove the /category/ base
add_filter('term_link', function ($url, $term, $taxonomy) {
    if ('category' === $taxonomy) {
        return home_url(trailingslashit($term->slug));
    }
    return $url;
}, 10, 3);

// Add rewrite rules to handle the new URL structure
add_action('init', function () {
    add_rewrite_tag('%category_name%', '([^/]+)');
    
    // Rule for paginated category archives, e.g., /blog/page/2
    add_rewrite_rule('^([^/]+)/page/?([0-9]{1,})/?$', 'index.php?category_name=$matches[1]&paged=$matches[2]', 'top');
    
    // Rule for the main category archive, e.g., /blog
    add_rewrite_rule('^([^/]+)/?$', 'index.php?category_name=$matches[1]', 'top');
});

// Redirect old /category/slug URLs to new /slug URLs
add_action('template_redirect', function () {
    if (is_category() && strpos($_SERVER['REQUEST_URI'], '/category/') !== false) {
        $term = get_queried_object();
        if ($term instanceof \WP_Term) {
            $new_url = home_url(trailingslashit($term->slug));
            wp_safe_redirect($new_url, 301);
            exit();
        }
    }
});----*/

/*--- REDIRECT ---*/

add_action('template_redirect', function() {
    // Redirect from /zespol/ to /poznaj-nasz-zespol/
    if (is_page('zespol') || (isset($_SERVER['REQUEST_URI']) && strtok($_SERVER['REQUEST_URI'], '?') === '/zespol/')) {
        $target_url = home_url('/poznaj-nasz-zespol/');
        wp_redirect($target_url, 301);
        exit();
    }

    // Redirect from the CPT 'offer' archive to the '/nasza-oferta/' page
    if (is_post_type_archive('offer')) {
        $target_url = home_url('/nasza-oferta/');
        wp_redirect($target_url, 301);
        exit();
    }
    
    // Redirect from the old /oferta/ page to /nasza-oferta/
    if (is_page('oferta')) {
        $target_url = home_url('/nasza-oferta/');
        wp_redirect($target_url, 301);
        exit();
    }

    // Redirect from /shop/ to the homepage
    if (is_shop()) {
        $target_url = home_url('/');
        wp_redirect($target_url, 301);
        exit();
    }
});


/*--- PAGINATION ---*/

namespace App;

add_filter('the_posts_pagination_args', function ($args) {
    $args['prev_text'] = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>';
    $args['next_text'] = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>';
    $args['screen_reader_text'] = __('Nawigacja po wpisach', 'sage');
    return $args;
});

add_filter('navigation_markup_template', function ($template, $class) {
    return '
    <nav class="navigation %1$s mt-10 mb-10" aria-label="%4$s">
        <h2 class="screen-reader-text">%2$s</h2>
        <div class="nav-links flex items-center justify-center gap-4">%3$s</div>
    </nav>';
}, 10, 2);

add_filter('paginate_links_output', function ($output) {
    $output = str_replace('page-numbers', 'page-numbers inline-flex items-center justify-center w-10 h-10 rounded-full', $output);
    $output = str_replace('current', 'current bg-primary text-white', $output);
    $output = str_replace('prev', 'prev', $output);
    $output = str_replace('next', 'next', $output);
    return $output;
});


/*--- CHECKOUT SHIPPING ---*/
add_action('woocommerce_check_cart_items', function () {
    if (is_admin() || (defined('DOING_AJAX') && DOING_AJAX)) {
        return; // nie ruszamy podczas AJAX update_checkout
    }
    if (! WC()->session) {
        return;
    }
    if (WC()->session->get('shipping_reset_done')) {
        return;
    }
    WC()->session->set('chosen_shipping_methods', []);
    WC()->session->set('shipping_reset_done', true);
});

/*--- CATEGORY URL ---*/

add_filter('category_rewrite_rules', function($rules) {
    $new_rules = [];
    $categories = get_categories(['hide_empty' => false]);

    foreach ($categories as $category) {
        $new_rules[$category->slug . '/?$'] = 'index.php?category_name=' . $category->slug;
    }

    return $new_rules;
});

/*--- LOYALTY MAIL: 5. REZERWACJA AMELIA ---*/

add_action('amelia_booking_status_updated', __NAMESPACE__ . '\\maybe_send_fifth_amelia_email', 20, 1);
add_action('amelia_after_booking_added',    __NAMESPACE__ . '\\maybe_send_fifth_amelia_email', 20, 1);

function maybe_send_fifth_amelia_email($booking)
{
    // Hooki Amelii przekazują tablicę z danymi rezerwacji
    if (! is_array($booking)) {
        return;
    }

    // Status rezerwacji – bierzemy pod uwagę tylko „realne”
    $status = $booking['status'] ?? '';
    if (! in_array($status, ['approved', 'pending'], true)) {
        return;
    }

    $customer_id = (int) ($booking['customerId'] ?? 0);
    $email       = $booking['info']['email'] ?? '';

    // Fallback: e-mail z customera, jeżeli „info” jest pustym JSON-em
    if (! is_email($email) && $customer_id) {
        global $wpdb;
        $users_table = \get_amelia_table_name('users');
        if ($users_table) {
            $email = $wpdb->get_var($wpdb->prepare(
                "SELECT email FROM {$users_table} WHERE id = %d",
                $customer_id
            ));
        }
    }

    if (! $customer_id || ! is_email($email)) {
        return;
    }

    // Idempotencja – flaga per klient Amelii
    $flag_key = '_loyalty_5_amelia_sent_' . $customer_id;
    if (get_option($flag_key)) {
        return;
    }

    // Liczymy zatwierdzone/oczekujące rezerwacje tego klienta
    global $wpdb;
    $bookings_table = \get_amelia_table_name('customer_bookings');
    if (! $bookings_table) {
        return;
    }

    $count = (int) $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM {$bookings_table}
         WHERE customerId = %d
         AND status IN ('approved','pending')",
        $customer_id
    ));

    if ($count < 5) {
        return;
    }

    $subject  = 'Kontynuacja współpracy – prosimy o wypełnienie formularza';
    $heading  = 'Kontynuacja współpracy';
    $form_url = 'https://docs.google.com/forms/d/e/1FAIpQLSe1b7iCjmuZUwalYq8wrmw7Zfu9H5DiFdDFb_27RZFnCRuRlQ/viewform?usp=dialog';

    $body  = '<p>Dzień dobry,</p>';
    $body .= '<p>dziękujemy za dotychczasowe spotkania i zaufanie, jakim nas Państwo obdarzają.</p>';
    $body .= '<p>W związku z kontynuacją współpracy, prosimy o wypełnienie krótkiego formularza. Zawiera on:</p>';
    $body .= '<ul>'
           . '<li>potwierdzenie zapoznania się i akceptacji kontraktu,</li>'
           . '<li>potwierdzenie zapoznania się z zasadami RODO oraz wyrażenie zgody.</li>'
           . '</ul>';
    $body .= '<p><a href="' . esc_url($form_url) . '" target="_blank" rel="noopener noreferrer">Link do formularza</a></p>';
    $body .= '<p>Wypełnienie formularza zajmuje tylko chwilę i pozwala nam zadbać o przejrzyste oraz bezpieczne zasady dalszej pracy.</p>';
    $body .= '<p>Dziękujemy!</p>';

    // Owijamy w szablon WooCommerce (jeśli WC aktywne) lub wysyłamy „goły” HTML
    if (function_exists('WC') && WC()->mailer()) {
        $wrapped = WC()->mailer()->wrap_message($heading, $body);
    } else {
        $wrapped = '<h2>' . esc_html($heading) . '</h2>' . $body;
    }

    $headers = ['Content-Type: text/html; charset=UTF-8'];

    if (wp_mail($email, $subject, $wrapped, $headers)) {
        update_option($flag_key, current_time('mysql'), false);
    }
}

/*--- KONTRAKT PDF DLA PRODUKTU 509 ---*/
add_filter('woocommerce_email_attachments', function ($attachments, $email_id, $order) {
    if ($email_id !== 'customer_completed_order' || ! $order instanceof \WC_Order) {
        return $attachments;
    }

    $has_509 = false;
    foreach ($order->get_items() as $item) {
        if ((int) $item->get_product_id() === 509 || (int) $item->get_variation_id() === 509) {
            $has_509 = true;
            break;
        }
    }

    if ($has_509) {
        $upload_dir = wp_get_upload_dir();
        $file = trailingslashit($upload_dir['basedir']) . '2026/05/Kontrakt-terapeutyczny-2026.pdf';

        if (file_exists($file) && is_readable($file)) {
            $attachments[] = $file;
        }
    }

    return $attachments;
}, 10, 3);