<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class AmeliaBookingHeader extends Block
{
    public $name = 'Nagłówek Rezerwacji Amelia';
    public $description = 'Wyświetla nagłówek i formularz rezerwacji Amelia.';
    public $slug = 'amelia-booking-header'; // Zmieniony slug
    public $category = 'formatting';
    public $icon = 'calendar-alt';
    public $keywords = ['rezerwacja', 'amelia', 'kalendarz', 'booking'];
    public $mode = 'edit';
    public $supports = [
        'align' => false,
        'mode' => false,
        'jsx' => true,
        'anchor' => true,
    ];

    public function fields()
    {
        $ameliaHeader = new FieldsBuilder('amelia_booking_header');

        $ameliaHeader
            ->setLocation('block', '==', 'acf/amelia-booking-header') // Ważne - dopasowane do sluga

            ->addTab('Zawartość', ['placement' => 'top'])
            ->addSelect('amelia_service', [
                'label' => 'Wybierz usługę',
                'instructions' => 'Wybierz usługę do rezerwacji. Pole zostanie wypełnione automatycznie.',
                'allow_null' => 1,
                'ui' => 1,
            ])
            ->addSelect('amelia_employee', [
                'label' => 'Wybierz pracownika',
                'instructions' => 'Wybierz pracownika. Pole zostanie wypełnione automatycznie.',
                'allow_null' => 1,
                'ui' => 1,
            ])
            ->addSelect('amelia_location', [
                'label' => 'Wybierz lokalizację',
                'instructions' => 'Wybierz lokalizację. Pole zostanie wypełnione automatycznie.',
                'allow_null' => 1,
                'ui' => 1,
            ])
            ->addTrueFalse('show_photo', [
                'label' => 'Pokaż zdjęcie pracownika',
                'ui' => 1,
                'default_value' => 1,
            ])
            
            // --- SKOPIOWANE "USTAWIENIA BLOKU" Z TWOJEGO PRZYKŁADU ---
            ->addTab('Ustawienia bloku', ['placement' => 'top'])
            ->addText('section_id', ['label' => 'ID sekcji'])
            ->addText('section_class', ['label' => 'Dodatkowe klasy CSS'])
            ->addSelect('background', [
                'label' => 'Kolor tła',
                'choices' => [
                    'none' => 'Brak (domyślne)',
                    'section-white' => 'Białe',
                    'section-light' => 'Jasne',
                    'section-gray' => 'Szare',
                    'section-brand' => 'Marki',
                    'section-gradient' => 'Gradient',
                    'section-dark' => 'Ciemne',
                ],
                'default_value' => 'none',
                'allow_null' => 0,
            ]);

        return $ameliaHeader;
    }

    public function with()
    {
        return [
            // Dane z ACF
            'service_id' => get_field('amelia_service'),
            'employee_id' => get_field('amelia_employee'),
            'location_id' => get_field('amelia_location'),
            'show_photo' => get_field('show_photo'),
            
            // Dane z ustawień bloku
            'section_id' => get_field('section_id'),
            'section_class' => get_field('section_class'),
            'background' => get_field('background'),
            
            // Pobierane dane Amelii
            'amelia_data' => $this->getAmeliaData(),
        ];
    }

    public function getAmeliaData()
    {
        global $wpdb;
        $service_id = get_field('amelia_service');
        $employee_id = get_field('amelia_employee');
        $location_id = get_field('amelia_location');
        
        $data = [
            'service_name' => '',
            'employee_name' => '',
            'employee_photo' => '',
            'location_name' => '',
        ];

        // Pracownik
        if ($employee_id) {
            $users_table = get_amelia_table_name('users');
            if ($users_table) {
                $employee = $wpdb->get_row($wpdb->prepare("SELECT firstName, lastName, pictureFullPath FROM {$users_table} WHERE id = %d", $employee_id));
                if ($employee) {
                    $data['employee_name'] = trim($employee->firstName . ' ' . $employee->lastName);
                    $data['employee_photo'] = $employee->pictureFullPath;
                }
            }
        }
        
        // Usługa
        if ($service_id) {
            $services_table = get_amelia_table_name('services');
            if ($services_table) {
                $data['service_name'] = $wpdb->get_var($wpdb->prepare("SELECT name FROM {$services_table} WHERE id = %d", $service_id));
            }
        }
        
        // Lokalizacja
        if ($location_id) {
            $locations_table = get_amelia_table_name('locations');
            if ($locations_table) {
                $data['location_name'] = $wpdb->get_var($wpdb->prepare("SELECT name FROM {$locations_table} WHERE id = %d", $location_id));
            }
        }
        
        return $data;
    }
}