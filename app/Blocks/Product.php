<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class Product extends Block
{
	public $name = 'Produkt';
	public $description = 'product';
	public $slug = 'product';
	public $category = 'formatting';
	public $icon = 'columns';
	public $keywords = ['product'];
	public $mode = 'edit';
	public $supports = [
		'align' => false,
		'mode' => false,
		'jsx' => true,
	];

	public function fields()
	{
		$product = new FieldsBuilder('product');

		$product
			->setLocation('block', '==', 'acf/product') // ważne!
			->addText('block-title', [
				'label' => 'Tytuł',
				'required' => 0,
			])
			->addAccordion('accordion1', [
				'label' => 'Produkt',
				'open' => false,
				'multi_expand' => true,
			])
			/*--- FIELDS ---*/
			->addTab('Kolumna #1', ['placement' => 'top'])
			->addGroup('g_product', ['label' => ''])
			->addText('title', ['label' => 'Tytuł'])
			->addWysiwyg('content', [
				'label' => 'Treść',
				'tabs' => 'all', // 'visual', 'text', 'all'
				'toolbar' => 'full', // 'basic', 'full'
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
			->addWysiwyg('when', [
				'label' => 'Kiedy warto',
				'tabs' => 'all', // 'visual', 'text', 'all'
				'toolbar' => 'full', // 'basic', 'full'
				'media_upload' => true,
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



		return $product;
	}

	public function with(): array
	{
		$fields = [
			'g_product' => $this->get_product_data(get_field('g_product')),

			'section_id' => get_field('section_id'),
			'section_class' => get_field('section_class'),

			'flip' => (bool) get_field('flip'),
			'wide' => (bool) get_field('wide'),
			'nomt' => (bool) get_field('nomt'),
			'gap' => (bool) get_field('gap'),
			'nolist' => (bool) get_field('nolist'),

			'background' => get_field('background') ?: 'none',
		];

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
					'permalink' => $product->get_permalink(), // Dodana linia
					'short_description' => $product->get_short_description(),
					'description' => $product->get_description(),
				];
			}
		}
		return $col_data;
	}
}
