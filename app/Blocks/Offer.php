<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class Offer extends Block
{
	public $name = 'Oferta';
	public $description = 'offer';
	public $slug = 'offer';
	public $category = 'formatting';
	public $icon = 'businessperson';
	public $keywords = ['oferta', 'offer', 'cpt'];
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
		$offer = new FieldsBuilder('offer');

		$offer
			->setLocation('block', '==', 'acf/offer')
			->addText('block-title', [
				'label' => 'Tytuł',
				'required' => 0,
			])
			->addAccordion('accordion1', [
				'label' => 'Oferta',
				'open' => false,
				'multi_expand' => true,
			])
			->addTab('Elementy', ['placement' => 'top'])
			->addGroup('g_offer', ['label' => ''])
			->addText('header', ['label' => 'Nagłówek'])
			->addWysiwyg('txt', [
				'label' => 'Treść',
				'tabs' => 'all',
				'toolbar' => 'full',
				'media_upload' => true,
			])
			->addImage('image', [
				'label' => 'Obraz w tle #1',
				'return_format' => 'array',
				'preview_size' => 'thumbnail',
			])
			->addImage('image2', [
				'label' => 'Obraz w tle #2',
				'return_format' => 'array',
				'preview_size' => 'thumbnail',
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

		return $offer;
	}

	public function with()
	{
		$offers_count = get_field('count') ?: 'all';
		$limit = $offers_count === '4' ? 4 : -1;

		return [
			'offer' => $this->getoffer($limit),
			'g_offer' => get_field('g_offer'),
			'section_id' => get_field('section_id'),
			'section_class' => get_field('section_class'),
			'flip' => get_field('flip'),
			'wide' => get_field('wide'),
			'nomt' => get_field('nomt'),
			'gap' => get_field('gap'),
			'background' => get_field('background'),
			'show_all_button' => $offers_count === '4',
		];
	}

	public function getoffer($limit = -1)
	{
		$args = [
			'post_type'      => 'offer',
			'posts_per_page' => $limit,
			'orderby'        => 'date',
			'order'          => 'DESC',
		];

		$query = new \WP_Query($args);

		return $query->posts;
	}
}
