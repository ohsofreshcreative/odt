<?php

use Roots\Acorn\Application;

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our theme. We will simply require it into the script here so that we
| don't have to worry about manually loading any of our classes later on.
|
*/

if (! file_exists($composer = __DIR__.'/vendor/autoload.php')) {
    wp_die(__('Error locating autoloader. Please run <code>composer install</code>.', 'sage'));
}

require $composer;

/*
|--------------------------------------------------------------------------
| Register The Bootloader
|--------------------------------------------------------------------------
|
| The first thing we will do is schedule a new Acorn application container
| to boot when WordPress is finished loading the theme. The application
| serves as the "glue" for all the components of Laravel and is
| the IoC container for the system binding all of the various parts.
|
*/

Application::configure()
    ->withProviders([
        App\Providers\ThemeServiceProvider::class,
    ])
    ->boot();

/*
|--------------------------------------------------------------------------
| Register Sage Theme Files
|--------------------------------------------------------------------------
|
| Out of the box, Sage ships with categorically named theme files
| containing common functionality and setup to be bootstrapped with your
| theme. Simply add (or remove) files from the array below to change what
| is registered alongside Sage.
|
*/

/*--- HIDE ALL BLOCKS ---*/

function allow_only_selected_blocks( $allowed_block_types, $editor_context ) {
    if ( ! empty( $editor_context->post ) ) {
        // Pobierz wszystkie zarejestrowane bloki
        $all_blocks = WP_Block_Type_Registry::get_instance()->get_all_registered();

        $allowed_blocks = [];

        foreach ( $all_blocks as $block_name => $block ) {
            // Dopuszczone kategorie
            if ( isset( $block->category ) && in_array( $block->category, ['formatting', '_media', '_con', '_tekst'], true ) ) {
                $allowed_blocks[] = $block_name;
            }

            // Dopuszczamy też wszystkie bloki ACF (prefix "acf/")
            if ( strpos( $block_name, 'acf/' ) === 0 ) {
                $allowed_blocks[] = $block_name;
            }
        }

        // Dodatkowo dopuszczamy akapit i nagłówek
        $allowed_blocks[] = 'core/paragraph';
        $allowed_blocks[] = 'core/heading';
        $allowed_blocks[] = 'core/list';

        return $allowed_blocks;
    }

    return [];
}

add_filter( 'allowed_block_types_all', 'allow_only_selected_blocks', 10, 2 );




collect(['setup', 'filters'])
    ->each(function ($file) {
        if (! locate_template($file = "app/{$file}.php", true, true)) {
            wp_die(
                /* translators: %s is replaced with the relative file path */
                sprintf(__('Error locating <code>%s</code> for inclusion.', 'sage'), $file)
            );
        }
    });


/*--- PROJECT BLOCKS ---*/

add_filter('sage/acf-composer/fields', fn () => [
    App\Blocks\ExampleBlock::class,
]);


/**
 * ==================================================================
 * Funkcje pomocnicze dla bloku Nagłówek Rezerwacji Amelia
 * ==================================================================
 */

/**
 * Funkcja pomocnicza do znajdowania poprawnej nazwy tabeli Amelii.
 */
function get_amelia_table_name($base_name) {
    global $wpdb;
    $candidates = [$wpdb->prefix . 'amelia_' . $base_name, $wpdb->prefix . 'wpamelia_' . $base_name];
    foreach ($candidates as $t) {
        if ($wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $t)) === $t) {
            return $t;
        }
    }
    return null;
}

/**
 * Wczytuje skrypty dla panelu admina.
 */
add_action('admin_enqueue_scripts', function ($hook) {
    // Wczytaj skrypt tylko na ekranach edycji postów i stron
    if ('post.php' !== $hook && 'post-new.php' !== $hook) {
        return;
    }

    // Wczytaj nasz dedykowany skrypt dla bloku Amelia, używając manifestu Vite
    wp_enqueue_script(
        'sage/admin-amelia-block.js', 
        \App\asset('scripts/admin-amelia-block.js')->uri(), 
        ['acf-input'], // Zależność od ACF
        null, 
        true
    );

    // Przekaż adres URL i nonce do naszego skryptu
    wp_localize_script(
        'sage/admin-amelia-block.js',
        'ameliaBlockAjax', // Nazwa obiektu JS
        [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('amelia_filter_nonce') // Nonce dla bezpieczeństwa
        ]
    );
});

/**
 * Endpoint AJAX do dynamicznego filtrowania opcji Amelia.
 */
add_action('wp_ajax_get_amelia_acf_options', function () {
    check_ajax_referer('amelia_filter_nonce', 'nonce');
    global $wpdb;

    $employee_id = isset($_POST['employee_id']) && $_POST['employee_id'] ? (int)$_POST['employee_id'] : 0;
    $service_id = isset($_POST['service_id']) && $_POST['service_id'] ? (int)$_POST['service_id'] : 0;
    $location_id = isset($_POST['location_id']) && $_POST['location_id'] ? (int)$_POST['location_id'] : 0;

    $tables = [
        'users' => get_amelia_table_name('users'),
        'services' => get_amelia_table_name('services'),
        'locations' => get_amelia_table_name('locations'),
        'providers_to_services' => get_amelia_table_name('providers_to_services'),
        'providers_to_locations' => get_amelia_table_name('providers_to_locations'),
    ];

    $response = [
        'employees' => [],
        'services' => [],
        'locations' => [],
    ];

    // --- Dynamiczne budowanie zapytań SQL ---
    $employee_query = "SELECT id, firstName, lastName FROM {$tables['users']} WHERE status = 'visible' AND type = 'provider'";
    $service_query = "SELECT id, name FROM {$tables['services']} WHERE status = 'visible'";
    $location_query = "SELECT id, name FROM {$tables['locations']}"; // Zakładamy, że lokalizacje są zawsze widoczne

    if ($service_id) {
        $employee_query .= $wpdb->prepare(" AND id IN (SELECT userId FROM {$tables['providers_to_services']} WHERE serviceId = %d)", $service_id);
    }
    if ($location_id) {
        $employee_query .= $wpdb->prepare(" AND id IN (SELECT userId FROM {$tables['providers_to_locations']} WHERE locationId = %d)", $location_id);
    }

    if ($employee_id) {
        $service_query .= $wpdb->prepare(" AND id IN (SELECT serviceId FROM {$tables['providers_to_services']} WHERE userId = %d)", $employee_id);
    }
    
    // --- Pobieranie danych i formatowanie dla ACF ---
    $response['employees'][''] = 'Dowolny pracownik';
    foreach($wpdb->get_results($employee_query) as $item) {
        $response['employees'][$item->id] = trim($item->firstName . ' ' . $item->lastName);
    }

    $response['services'][''] = 'Dowolna usługa';
    foreach($wpdb->get_results($service_query) as $item) {
        $response['services'][$item->id] = $item->name;
    }
    
    $response['locations'][''] = 'Dowolna lokalizacja';
    foreach($wpdb->get_results($location_query) as $item) {
        $response['locations'][$item->id] = $item->name;
    }

    wp_send_json_success($response);
});