<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class Slider extends Block
{
    public $name = 'Slider - Standard';
    public $description = 'slider';
    public $slug = 'slider';
    public $category = 'formatting';
    public $icon = 'image-flip-horizontal';
    public $keywords = ['slider', 'kafelki'];
    public $mode = 'edit';
    public $supports = [
        'align' => false,
        'mode' => false,
        'jsx' => true,
    ];

    public function fields()
    {
        $slider = new FieldsBuilder('slider');

        $slider
            ->setLocation('block', '==', 'acf/slider') // ważne!
            ->addText('block-title', [
                'label' => 'Tytuł',
                'required' => 0,
            ])
            ->addAccordion('accordion1', [
                'label' => 'Slider - Kafelki',
                'open' => false,
                'multi_expand' => true,
            ])
            /*--- FIELDS ---*/
            ->addTab('Treści', ['placement' => 'top'])
            ->addGroup('g_slider', ['label' => ''])

            ->addText('title', ['label' => 'Tytuł'])

            ->addRepeater('r_slider', [
                'label' => 'Slider',
                'layout' => 'table', // 'row', 'block', albo 'table'
                'min' => 1,
                'max' => 10,
                'button_label' => 'Dodaj kafelek'
            ])
            ->addImage('image', [
                'label' => 'Zdjęcie - tło',
                'return_format' => 'array', // lub 'url', lub 'id'
                'preview_size' => 'thumbnail',
            ])
            ->addImage('icon', [
                'label' => 'Ikonka',
                'return_format' => 'array',
                'preview_size' => 'thumbnail',
            ])
            ->addText('header', [
                'label' => 'Nagłówek',
            ])
            ->addTextarea('opis', [
                'label' => 'Opis',
                'rows' => 4,
                'new_lines' => 'br',
            ])
            ->endRepeater()

            ->endGroup()

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

        return $slider;
    }

    public function with()
    {
        return [
            'g_slider' => get_field('g_slider'),
            'slider' => get_field('g_slider')['r_slider'] ?? [],
            'section_id' => get_field('section_id'),
            'section_class' => get_field('section_class'),
            'nolist' => get_field('nolist'),
            'flip' => get_field('flip'),
            'wide' => get_field('wide'),
            'nomt' => get_field('nomt'),
            'gap' => get_field('gap'),
            'background' => get_field('background'),
        ];
    }
}