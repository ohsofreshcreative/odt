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
 * Ładuje dane pracowników (TYLKO SPECJALISTÓW) Amelii do pola select w ACF.
 */
add_filter('acf/load_field/name=amelia_employee', function ($field) {
    global $wpdb;
    $users_table = get_amelia_table_name('users');

    if (!$users_table) {
        $field['choices'] = ['' => 'Nie znaleziono tabeli Amelia Users'];
        return $field;
    }
    
    // KLUCZOWA ZMIANA: Dodajemy warunek "WHERE type = 'provider'"
    $employees = $wpdb->get_results("SELECT id, firstName, lastName FROM `{$users_table}` WHERE status = 'visible' AND type = 'provider' ORDER BY firstName ASC", ARRAY_A);
    
    $field['choices'] = ['' => 'Wybierz pracownika']; // Opcja domyślna
    if ($employees) {
        foreach ($employees as $employee) {
            $field['choices'][$employee['id']] = trim($employee['firstName'] . ' ' . $employee['lastName']);
        }
    }
    return $field;
});

/**
 * Ładuje dane usług Amelii do pola select w ACF.
 */
add_filter('acf/load_field/name=amelia_service', function ($field) {
    global $wpdb;
    $services_table = get_amelia_table_name('services');
    
    if (!$services_table) {
        $field['choices'] = ['' => 'Nie znaleziono tabeli Amelia Services'];
        return $field;
    }

    $services = $wpdb->get_results("SELECT id, name FROM `{$services_table}` WHERE status = 'visible' ORDER BY name ASC", ARRAY_A);
    
    $field['choices'] = ['' => 'Wybierz usługę'];
    if ($services) {
        foreach ($services as $service) {
            $field['choices'][$service['id']] = $service['name'];
        }
    }
    return $field;
});

/**
 * Ładuje dane lokalizacji Amelii do pola select w ACF.
 */
add_filter('acf/load_field/name=amelia_location', function ($field) {
    global $wpdb;
    $locations_table = get_amelia_table_name('locations');

    if (!$locations_table) {
        $field['choices'] = ['' => 'Nie znaleziono tabeli Amelia Locations'];
        return $field;
    }
     
    $locations = $wpdb->get_results("SELECT id, name FROM `{$locations_table}`", ARRAY_A);
    
    $field['choices'] = ['' => 'Wybierz lokalizację'];
    if ($locations) {
        foreach ($locations as $location) {
            $field['choices'][$location['id']] = $location['name'];
        }
    }
    return $field;
});

/**
 * Pozwala pracownikom na rezerwowanie terminów poza ich standardowymi godzinami pracy
 * podczas tworzenia wizyty z panelu administracyjnego WordPress (z uwzględnieniem AJAX).
 */
add_filter('amelia_is_time_slot_available', function($available, $service, $providerId, $start, $end, $persons, $locationId, $booking = null) {
    // Sprawdź, czy żądanie pochodzi z panelu administracyjnego lub jest to żądanie AJAX wywołane z panelu.
    // Daje to większą pewność, że modyfikujemy tylko zachowanie w backendzie.
    if (is_admin() || (defined('DOING_AJAX') && DOING_AJAX)) {
        // Zawsze zwracaj true, aby pominąć walidację godzin pracy dla pracownika w backendzie.
        return true;
    }

    // Dla rezerwacji od strony klienta (frontend), zachowaj standardową logikę walidacji.
    return $available;
}, 20, 8); // Zwiększyłem priorytet do 20, aby upewnić się, że nasza funkcja odpali się jako jedna z ostatnich.


add_action('wp_enqueue_scripts', function () {
  if (function_exists('acf_enqueue_scripts')) {
    acf_enqueue_scripts();
  }
}, 20); 

/*--- CRON AMELIA ---*/

add_action('my_amelia_cron', function() {
    wp_remote_get('https://osrodekdobrejterapii.pl/wp-admin/admin-ajax.php?action=wpamelia_api&call=/notifications/scheduled/send');
});

if (!wp_next_scheduled('my_amelia_cron')) {
    wp_schedule_event(time(), 'hourly', 'my_amelia_cron');
}