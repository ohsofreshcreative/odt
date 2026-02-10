<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;
use App\Support\SectionClasses;

class Prices extends Block
{
	public $name = 'Cennik';
	public $description = 'prices';
	public $slug = 'prices';
	public $category = 'formatting';
	public $icon = 'table-row-after';
	public $keywords = ['prices', 'kafelki'];
	public $mode = 'edit';
	public $supports = [
		'align' => false,
		'mode' => false,
		'jsx' => true,
	];

	public function fields()
	{
		$prices = new FieldsBuilder('prices');

		$prices
			->setLocation('block', '==', 'acf/prices') // ważne!
			->addText('block-title', [
				'label' => 'Tytuł',
				'required' => 0,
			])
			->addAccordion('accordion1', [
				'label' => 'Cennik',
				'open' => false,
				'multi_expand' => true,
			])

			/*--- TAB #1 ---*/
			->addTab('Treści', ['placement' => 'top'])
			->addGroup('g_prices', ['label' => ''])
			->addText('header', ['label' => 'Nagłówek'])
			->addTextarea('text', [
				'label' => 'Opis',
				'rows' => 4,
				'new_lines' => 'br',
			])
			->addLink('button', [
				'label' => 'Przycisk',
				'return_format' => 'array',
			])
			->endGroup()

			/*--- TAB #2 ---*/
			->addTab('Kafelki', ['placement' => 'top'])
			->addRepeater('r_prices', [
				'label' => 'Kafelki',
				'layout' => 'table',
				'min' => 1,
				'button_label' => 'Dodaj kafelek',
			])
			->addImage('image', [
				'label' => 'Obraz',
				'return_format' => 'array',
				'preview_size' => 'thumbnail',
				'wrapper' => [
					'width' => '5',
				],
			])
			->addText('header', [
				'label' => 'Nagłówek',
				'required' => 1,
				'wrapper' => [
					'width' => '15',
				],
			])
			->addLink('button', [
				'label' => 'Przycisk',
				'return_format' => 'array',
				'wrapper' => [
					'width' => '15',
				],
			])
			->addRepeater('price_rows', [
				'label' => 'Cennik',
				'layout' => 'table',
				'button_label' => 'Dodaj pozycję',
			])
			->addText('label', [
				'label' => 'Usługa',
				'wrapper' => ['width' => '55'],
			])
			->addText('time', [
				'label' => 'Czas',
				'wrapper' => ['width' => '20'],
			])
			->addText('price', [
				'label' => 'Cena',
				'wrapper' => ['width' => '25'],
			])
			->endRepeater()
			->addLink('button2', [
				'label' => 'Dowiedź się więcej',
				'return_format' => 'array',
				'wrapper' => [
					'width' => '15',
				],
			])
			->endRepeater()

			/*--- TAB #3 ---*/
			->addTab('Podpis', ['placement' => 'top'])
			->addGroup('g_prices2', ['label' => ''])
			->addTextarea('text', [
				'label' => 'Opis',
				'rows' => 4,
				'new_lines' => 'br',
			])
			->endGroup()

			/*--- USTAWIENIA BLOKU ---*/
			->addTab('Ustawienia bloku', ['placement' => 'top'])
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

		return $prices;
	}

	public function with()
	{
		$r_prices = get_field('r_prices');

		$grouped_prices = [];
		foreach (($r_prices ?? []) as $item) {
			$tabName = trim((string) ($item['header'] ?? ''));
			if ($tabName === '') {
				continue;
			}
			$grouped_prices[$tabName][] = $item;
		}

		$fields = [
			'flip' => (bool) get_field('flip'),
			'wide' => (bool) get_field('wide'),
			'nomt' => (bool) get_field('nomt'),
			'gap' => (bool) get_field('gap'),
			'background' => get_field('background'),

		];

		$sectionClass = SectionClasses::fromMap($fields, [
			'flip' => 'order-flip',
			'wide' => 'wide',
			'nomt' => '!mt-0',
			'gap' => 'wider-gap',

		]);

		return [
			'g_prices' => get_field('g_prices'),
			'g_prices2' => get_field('g_prices2'),
			'r_prices' => $r_prices,
			'grouped_prices' => $grouped_prices,

			'section_id' => get_field('section_id'),
			'section_class' => get_field('section_class'),

			'flip' => $fields['flip'],
			'wide' => $fields['wide'],
			'nomt' => $fields['nomt'],
			'gap' => $fields['gap'],
			'background' => $fields['background'],

			'sectionClass' => $sectionClass,
		];
	}
}
