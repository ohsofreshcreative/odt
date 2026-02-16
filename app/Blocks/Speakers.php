<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;
use App\Support\SectionClasses;

class Speakers extends Block
{
    public $name = 'Wykładowcy w zespole';
    public $description = 'speakers';
    public $slug = 'speakers';
    public $category = 'formatting';
    public $icon = 'admin-users';
    public $keywords = ['speakers', 'nasz', 'zespol', 'kafelki', 'slider', 'eksperci'];
    public $mode = 'edit';
    public $supports = [
        'align' => false,
        'mode' => false,
        'jsx' => true,
        'anchor' => true,
        'customClassName' => true,
    ];

    public function fields()
    {
        $speakers = new FieldsBuilder('speakers');

        $speakers
            ->setLocation('block', '==', 'acf/speakers')
            ->addText('block-title', [
                'label' => 'Tytuł',
                'required' => 0,
            ])
            ->addAccordion('accordion1', [
                'label' => 'Wykładowcy w zespole',
                'open' => false,
                'multi_expand' => true,
            ])
            /*--- FIELDS ---*/
            ->addTab('Treści', ['placement' => 'top'])
            ->addGroup('g_speakers', ['label' => ''])
            ->addText('header', ['label' => 'Nagłówek'])
            ->addWysiwyg('content', [
                'label' => 'Treść',
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => true,
            ])
            ->addImage('image', [
                'label' => 'Obraz w tle',
                'return_format' => 'array',
                'preview_size' => 'thumbnail',
            ])
            ->endGroup()

            ->addTab('Wykładowcy', ['placement' => 'top'])
            ->addRepeater('r_speakers', [
                'label' => 'Wykładowcy',
                'button_label' => 'Dodaj wykładowcę',
                'layout' => 'block',
            ])
            ->addText('name', [
                'label' => 'Imię i nazwisko',
            ])
            ->addImage('photo', [
                'label' => 'Zdjęcie',
                'return_format' => 'array',
                'preview_size' => 'thumbnail',
            ])
            ->addGallery('logos', [
                'label' => 'Logotypy',
                'return_format' => 'array',
                'preview_size' => 'thumbnail',
            ])
            ->endRepeater()


            /*--- USTAWIENIA BLOKU ---*/

            ->addTab('Ustawienia bloku', ['placement' => 'top'])
            ->addText('section_id', [
                'label' => 'ID',
            ])
            ->addText('section_class', [
                'label' => 'Dodatkowe klasy CSS',
            ])
            ->addTrueFalse('nolist', [
                'label' => 'Brak punktatorów',
                'ui' => 1,
                'ui_on_text' => 'Tak',
                'ui_off_text' => 'Nie',
            ])
            ->addTrueFalse('flip', [
                'label' => 'Odwrotna kolejność',
                'ui' => 1,
                'ui_on_text' => 'Tak',
                'ui_off_text' => 'Nie',
            ])
            ->addTrueFalse('wide', [
                'label' => 'Szeroka kolumna',
                'ui' => 1,
                'ui_on_text' => 'Tak',
                'ui_off_text' => 'Nie',
            ])
            ->addTrueFalse('nomt', [
                'label' => 'Usunięcie marginesu górnego',
                'ui' => 1,
                'ui_on_text' => 'Tak',
                'ui_off_text' => 'Nie',
            ])
            ->addTrueFalse('gap', [
                'label' => 'Większy odstęp',
                'ui' => 1,
                'ui_on_text' => 'Tak',
                'ui_off_text' => 'Nie',
            ])
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
                'ui' => 0, // Ulepszony interfejs
                'allow_null' => 0,
            ]);

        return $speakers;
    }

    public function with(): array
    {
        $fields = [
            'r_speakers' => get_field('r_speakers'),
            'g_speakers' => get_field('g_speakers'),
            'section_id' => get_field('section_id'),
            'section_class' => get_field('section_class'),
            'nolist' => (bool) get_field('nolist'),
            'flip' => (bool) get_field('flip'),
            'wide' => (bool) get_field('wide'),
            'nomt' => (bool) get_field('nomt'),
            'gap' => (bool) get_field('gap'),
            'background' => get_field('background') ?: 'none',
        ];

        $fields['sectionClass'] = SectionClasses::fromMap($fields, [
            'nolist' => 'nolist',
            'flip' => 'order-flip',
            'wide' => 'wide',
            'nomt' => '!mt-0',
            'gap' => 'wider-gap',
        ]);

        return $fields;
    }
}