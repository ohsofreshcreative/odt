<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;
use App\Support\SectionClasses;

class Voucher extends Block
{
    public $name = 'Voucher';
    public $description = 'voucher';
    public $slug = 'voucher';
    public $category = 'formatting';
    public $icon = 'align-pull-left';
    public $keywords = ['tresc', 'zdjecie'];
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
        $voucher = new FieldsBuilder('voucher');

        $voucher
            ->setLocation('block', '==', 'acf/voucher') // ważne!
            ->addText('block-title', [
                'label' => 'Tytuł',
                'required' => 0,
            ])
            ->addAccordion('accordion1', [
                'label' => 'Voucher',
                'open' => false,
                'multi_expand' => true,
            ])
            /*--- GROUP ---*/
            ->addTab('Elementy', ['placement' => 'top'])
            ->addGroup('g_voucher', ['label' => ''])
            ->addImage('image', [
                'label' => 'Obraz',
                'return_format' => 'array',
                'preview_size' => 'thumbnail',
            ])
            ->addText('header', ['label' => 'Nagłówek'])
            ->addWysiwyg('txt', [
                'label' => 'Treść',
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => true,
            ])
            ->addPostObject('product', [
                'label' => 'Wybierz produkt',
                'post_type' => ['product'],
                'multiple' => false,
                'return_format' => 'object',
                'ui' => true,
            ])
            ->addText('btn', ['label' => 'Tekst przycisku'])
            ->addSelect('button_type', [
                'label' => 'Typ przycisku',
                'choices' => [
                    'add_to_cart' => 'Dodaj do koszyka',
                    'product_link' => 'Link do produktu',
                ],
                'default_value' => 'add_to_cart',
                'ui' => 1,
            ])
            ->addTrueFalse('hint', [
                'label' => 'Dodaj dymek',
                'ui' => 1,
                'ui_on_text' => 'Tak',
                'ui_off_text' => 'Nie',
            ])
            ->addImage('image_hint', [
                'label' => 'Obraz',
                'return_format' => 'array',
                'preview_size' => 'thumbnail',
            ])
            ->conditional('hint', '==', '1')
            ->addText('header_hint', ['label' => 'Nagłówek'])
            ->conditional('hint', '==', '1')
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

        return $voucher;
    }

    public function with(): array
    {
        $fields = [
            'g_voucher' => $this->get_product_data(get_field('g_voucher')),

            'section_id' => get_field('section_id'),
            'section_class' => get_field('section_class'),

            'flip' => (bool) get_field('flip'),
            'wide' => (bool) get_field('wide'),
            'nomt' => (bool) get_field('nomt'),
            'gap' => (bool) get_field('gap'),

            'background' => get_field('background') ?: 'none',
        ];

        $fields['sectionClass'] = SectionClasses::fromMap($fields, [
            'flip' => 'order-flip',
            'wide' => 'wide',
            'nomt' => '!mt-0',
            'gap' => 'wider-gap',
        ]);

        return $fields;
    }

    protected function get_product_data($col_data)
    {
        if (!empty($col_data['product']) && function_exists('wc_get_product')) {
            $product = wc_get_product($col_data['product']->ID);
            if ($product) {
                $col_data['product_data'] = [
                    'price_html' => $product->get_price_html(),
                    'add_to_cart_url' => $product->add_to_cart_url(),
                    'permalink' => $product->get_permalink(),
                    'short_description' => $product->get_short_description(),
                    'description' => $product->get_description(),
                ];
            }
        }
        return $col_data;
    }
}