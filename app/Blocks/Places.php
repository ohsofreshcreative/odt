<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;
use App\Support\SectionClasses;

class Places extends Block
{
	public $name = 'Gabinety';
	public $description = 'places';
	public $slug = 'places';
	public $category = 'formatting';
	public $icon = 'location';
	public $keywords = ['gabinety', 'places'];
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
		$places = new FieldsBuilder('places');

		$places
			->setLocation('block', '==', 'acf/places') // ważne!
			->addText('block-title', [
				'label' => 'Tytuł',
				'required' => 0,
			])
			->addAccordion('accordion1', [
				'label' => 'Gabinety',
				'open' => false,
				'multi_expand' => true,
			])

			/*--- GROUP #1 ---*/
			->addTab('Elementy #1', ['placement' => 'top'])
			->addGroup('g_places1', ['label' => ''])
			->addRadio('media_type', [
				'label' => 'Wybierz medium',
				'choices' => [
					'gallery' => 'Galeria',
					'map' => 'Mapa (iframe)',
				],
				'default_value' => 'gallery',
				'layout' => 'horizontal',
			])
			// GALLERY
			->addGallery('gallery', [
				'label' => 'Galeria obrazów',
				'preview_size' => 'thumbnail',
				'library' => 'all',
				'min' => 1,
				'max' => 10,
			])
				->conditional('media_type', '==', 'gallery')
			// MAP (IFRAME)
			->addTextarea('map_iframe', [
				'label' => 'Kod iframe mapy',
				'instructions' => 'Wklej tutaj kod iframe, np. z Google Maps.',
				'new_lines' => '',
			])
				->conditional('media_type', '==', 'map')
			->addText('title', ['label' => 'Tytuł'])
			->addWysiwyg('txt', [
				'label' => 'Treść',
				'tabs' => 'all',
				'toolbar' => 'full',
				'media_upload' => true,
			])
			->addLink('button', [
				'label' => 'Przycisk',
				'return_format' => 'array',
			])
			->endGroup()

			/*--- GROUP #2 ---*/
			->addTab('Elementy #2', ['placement' => 'top'])
			->addGroup('g_places2', ['label' => ''])
			->addRadio('media_type', [
				'label' => 'Wybierz medium',
				'choices' => [
					'gallery' => 'Galeria',
					'map' => 'Mapa (iframe)',
				],
				'default_value' => 'gallery',
				'layout' => 'horizontal',
			])
			// GALLERY
			->addGallery('gallery', [
				'label' => 'Galeria obrazów',
				'preview_size' => 'thumbnail',
				'library' => 'all',
				'min' => 1,
				'max' => 10,
			])
				->conditional('media_type', '==', 'gallery')
			// MAP (IFRAME)
			->addTextarea('map_iframe', [
				'label' => 'Kod iframe mapy',
				'instructions' => 'Wklej tutaj kod iframe, np. z Google Maps.',
				'new_lines' => '',
			])
				->conditional('media_type', '==', 'map')
			->addText('title', ['label' => 'Tytuł'])
			->addWysiwyg('txt', [
				'label' => 'Treść',
				'tabs' => 'all',
				'toolbar' => 'full',
				'media_upload' => true,
			])
			->addLink('button', [
				'label' => 'Przycisk',
				'return_format' => 'array',
			])
			->endGroup()

			/*--- USTAWIENIA BLOKU ---*/

			->addTab('Ustawienia bloku', ['placement' => 'top'])
			->addImage('imagebg', [
				'label' => 'Obraz w tle',
				'return_format' => 'array',
				'preview_size' => 'thumbnail',
			])
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

		return $places;
	}

	public function with(): array
	{
		$fields = [
			'g_places1' => get_field('g_places1'),
			'g_places2' => get_field('g_places2'),
			'imagebg' => get_field('imagebg'),

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
}