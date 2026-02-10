<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class BlockClone extends Block
{
    public $name = 'Blok Referencyjny ';
    public $description = 'Wyświetla blok z innej strony/wpisu.';
    public $slug = 'block-clone';
    public $category = 'formatting';
    public $icon = 'admin-links';
    public $keywords = ['klon', 'blok', 'odwołanie'];
    public $mode = 'edit';
    public $supports = [
        'align' => false,
        'mode' => false,
        'jsx' => true,
    ];

    public function fields()
    {
        $blockClone = new FieldsBuilder('block_clone');

        $blockClone
            ->setLocation('block', '==', 'acf/block-clone')
            ->addText('block-title', [
                'label' => 'Tytuł (opcjonalny)',
                'required' => 0,
            ])
            ->addAccordion('accordion1', [
                'label' => 'Blok Referencyjny',
                'open' => true,
                'multi_expand' => true,
            ])
			/*--- TAB #1 ---*/
			->addTab('Treści', ['placement' => 'top'])
            ->addPostObject('source_page', [
                'label' => 'Wybierz stronę źródłową',
                'instructions' => 'Wybierz stronę, z której chcesz sklonować blok.',
                'post_type' => ['page', 'post'],
                'allow_null' => 0,
                'multiple' => 0,
                'return_format' => 'id',
                'ui' => 1,
            ])
            ->addText('block_name', [
                'label' => 'Nazwa bloku do sklonowania',
                'instructions' => 'Wpisz nazwę bloku, np. "acf/slider". Możesz ją znaleźć w trybie edycji HTML bloku.',
                'required' => 1,
            ])

            /*--- USTAWIENIA BLOKU ---*/
            ->addTab('Ustawienia bloku', ['placement' => 'top'])
            ->addText('section_id', [
                'label' => 'ID',
            ])
            ->addText('section_class', [
                'label' => 'Dodatkowe klasy CSS',
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
                'ui' => 0,
                'allow_null' => 0,
            ]);

        return $blockClone->build();
    }

    public function with()
    {
        return [
            'source_page_id' => get_field('source_page'),
            'block_name' => get_field('block_name'),
            'block_title' => get_field('block-title'),
            'section_id' => get_field('section_id'),
            'section_class' => get_field('section_class'),
            'background' => get_field('background'),
        ];
    }
}