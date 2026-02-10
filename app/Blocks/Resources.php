<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class Resources extends Block
{
	public $name = 'Baza wiedzy';
	public $description = 'resources';
	public $slug = 'resources';
	public $category = 'formatting';
	public $icon = 'table-row-after';
	public $keywords = ['resources', 'kafelki'];
	public $mode = 'edit';
	public $supports = [
		'align' => false,
		'mode' => false,
		'jsx' => true,
	];

    public function fields()
    {
        $resources = new FieldsBuilder('resources');

        $resources
            ->setLocation('block', '==', 'acf/resources')

			->addText('block-title', [
				'label' => 'Tytuł',
				'required' => 0,
			])
			->addAccordion('accordion1', [
				'label' => 'Baza wiedzy',
				'open' => false,
				'multi_expand' => true,
			])
            ->addTab('Lewa kolumna')
            ->addGroup('col_1', ['label' => 'Ustawienia lewej kolumny'])
                ->addText('title', ['label' => 'Tytuł kolumny'])
                ->addTaxonomy('category', [
                    'label' => 'Wybierz kategorię wpisów',
                    'taxonomy' => 'category', // Domyślna taksonomia wpisów
                    'field_type' => 'select',
                    'return_format' => 'id', // Zwracamy ID kategorii
                    'allow_null' => 0,
                ])
            ->endGroup()

            ->addTab('Prawa kolumna')
            ->addGroup('col_2', ['label' => 'Ustawienia prawej kolumny'])
                ->addText('title', ['label' => 'Tytuł kolumny'])
                ->addTaxonomy('category', [
                    'label' => 'Wybierz kategorię wpisów',
                    'taxonomy' => 'category',
                    'field_type' => 'select',
                    'return_format' => 'id',
                    'allow_null' => 0,
                ])
            ->endGroup()
            
            ->addTab('Ustawienia')
            ->addTrueFalse('flip', [
                'label' => 'Odwrotna kolejność',
                'ui' => 1,
            ])
            ->addText('section_class', [
                'label' => 'Dodatkowe klasy CSS',
            ]);

        return $resources->build();
    }

    public function with()
    {
        return [
            'col_1_data' => get_field('col_1'),
            'col_2_data' => get_field('col_2'),
            'flip' => get_field('flip'),
            'section_class' => get_field('section_class'),
        ];
    }
}