<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;
use App\Support\SectionClasses;

class Wehelp extends Block
{
	public $name = 'Pomagamy w (lista)';
	public $description = 'wehelp';
	public $slug = 'wehelp';
	public $category = 'formatting';
	public $icon = 'align-full-width';
	public $keywords = ['hero', 'oferta'];
	public $mode = 'edit';
	public $supports = [
		'align' => false,
		'mode' => false,
		'jsx' => true,
	];

	public function fields()
	{
		$wehelp = new FieldsBuilder('wehelp');

		$wehelp
			->setLocation('block', '==', 'acf/wehelp') // ważne!
			->addText('block-title', [
				'label' => 'Tytuł',
				'required' => 0,
			])
			->addAccordion('accordion1', [
				'label' => 'Pomagamy w (lista)',
				'open' => false,
				'multi_expand' => true,
			])
			->addTab('Treść', ['placement' => 'top'])
			->addGroup('g_wehelp', ['label' => 'Hero - Produkt'])
			->addImage('image', [
				'label' => 'Obraz',
				'return_format' => 'array', // lub 'url', lub 'id'
				'preview_size' => 'thumbnail',
			])
			->addText('header', [
				'label' => 'Nagłówek',
				'required' => 1,
			])
			->addTextarea('txt', [
				'label' => 'Opis',
				'rows' => 4,
				'new_lines' => 'br',
			])
			->addLink('button1', [
				'label' => 'Przycisk #1',
				'return_format' => 'array',
			])
			->addLink('button2', [
				'label' => 'Przycisk #2',
				'return_format' => 'array',
			])

			->endGroup()

			/*--- USTAWIENIA BLOKU ---*/
			->addTab('Ustawienia bloku', ['placement' => 'top'])
			->addSelect('count', [
				'label' => 'Liczba ofert do wyświetlenia',
				'choices' => [
					'all' => 'Wszystkie',
					'4'   => '4',
				],
				'default_value' => 'all',
				'ui' => 0,
				'allow_null' => 0,
			])
			->addText('section_id', [
				'label' => 'ID',
			])
			->addText('section_class', [
				'label' => 'Dodatkowe klasy CSS',
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
				'ui' => 0,
				'allow_null' => 0,
			]);

		return $wehelp;
	}
public function with(): array
	{
		$supports_count = get_field('count') ?: 'all';
		$limit = $supports_count === '4' ? 4 : -1;

		$fields = [
			'support' => $this->getsupport($limit),
			'g_wehelp' => get_field('g_wehelp'),
			'section_id' => get_field('section_id'),
			'section_class' => get_field('section_class'),
			'background' => get_field('background') ?: 'none',
			'show_all_button' => $supports_count === '4',

			'flip' => (bool) get_field('flip'),
			'wide' => (bool) get_field('wide'),
			'nomt' => (bool) get_field('nomt'),
			'gap' => (bool) get_field('gap'),
		];

		$fields['sectionClass'] = SectionClasses::fromMap($fields, [
			'flip' => 'order-flip',
			'wide' => 'wide',
			'nomt' => '!mt-0',
			'gap' => 'wider-gap',
		]);

		return $fields;
	}

	public function getsupport(int $limit = -1): array
	{
		$args = [
			'post_type' => 'help',
			'posts_per_page' => $limit,
			'orderby' => 'date',
			'order' => 'DESC',
		];

		$query = new \WP_Query($args);

		return $query->posts;
	}
	
}
