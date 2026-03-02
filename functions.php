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
 * Rejestruje skrypty JS dla dynamicznych bloków ACF.
 */
add_action('acf/input/admin_enqueue_scripts', function () {
    $theme_uri = get_template_directory_uri();
    // Zakładamy, że skrypt będzie w `resources/scripts/admin-acf.js`
    wp_enqueue_script('admin-acf-scripts', $theme_uri . '/resources/scripts/admin-acf.js', ['acf-input'], null, true);
    wp_localize_script('admin-acf-scripts', 'acf_ajax', [
        'url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('acf_amelia_filter_nonce')
    ]);
});

/**
 * Endpoint AJAX do dynamicznego filtrowania opcji Amelia.
 */
add_action('wp_ajax_filter_amelia_fields', function () {
    check_ajax_referer('acf_amelia_filter_nonce');
    global $wpdb;

    $employee_id = isset($_POST['employee_id']) ? (int)$_POST['employee_id'] : 0;
    $service_id = isset($_POST['service_id']) ? (int)$_POST['service_id'] : 0;
    $location_id = isset($_POST['location_id']) ? (int)$_POST['location_id'] : 0;

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

    // --- Logika filtrowania ---

    $where_employees = "e.status = 'visible' AND e.type = 'provider'";
    $where_services = "s.status = 'visible'";
    $where_locations = "l.status = 'visible'";

    // Jeśli wybrano pracownika, filtruj usługi i lokalizacje
    if ($employee_id) {
        $where_services .= $wpdb->prepare(" AND s.id IN (SELECT serviceId FROM {$tables['providers_to_services']} WHERE userId = %d)", $employee_id);
        $where_locations .= $wpdb->prepare(" AND l.id IN (SELECT locationId FROM {$tables['providers_to_locations']} WHERE userId = %d)", $employee_id);
    }
    // Jeśli wybrano usługę, filtruj pracowników i lokalizacje
    if ($service_id) {
        $where_employees .= $wpdb->prepare(" AND e.id IN (SELECT userId FROM {$tables['providers_to_services']} WHERE serviceId = %d)", $service_id);
        // Usługa może ograniczać lokalizacje, jeśli jest do nich przypisana (zależy od konfiguracji Amelia)
    }
    // Jeśli wybrano lokalizację, filtruj pracowników
    if ($location_id) {
         $where_employees .= $wpdb->prepare(" AND e.id IN (SELECT userId FROM {$tables['providers_to_locations']} WHERE locationId = %d)", $location_id);
    }

    // --- Pobieranie danych ---
    $response['employees'] = $wpdb->get_results("SELECT e.id, e.firstName, e.lastName FROM {$tables['users']} e WHERE " . $where_employees, ARRAY_A);
    $response['services'] = $wpdb->get_results("SELECT s.id, s.name FROM {$tables['services']} s WHERE " . $where_services, ARRAY_A);
    $response['locations'] = $wpdb->get_results("SELECT l.id, l.name FROM {$tables['locations']} l WHERE " . $where_locations, ARRAY_A);

    wp_send_json_success($response);
});


/**
 * Poniższe filtry `acf/load_field` teraz głównie inicjują puste pola,
 * które będą wypełniane przez JavaScript.
 */
add_filter('acf/load_field/name=amelia_employee', function ($field) {
    $field['choices'] = ['' => 'Najpierw wybierz usługę/lokalizację...'];
    return $field;
});
add_filter('acf/load_field/name=amelia_service', function ($field) {
    $field['choices'] = ['' => 'Najpierw wybierz pracownika/lokalizację...'];
    return $field;
});
add_filter('acf/load_field/name=amelia_location', function ($field) {
    $field['choices'] = ['' => 'Najpierw wybierz pracownika...'];
    return $field;
});
