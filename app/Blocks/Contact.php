<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;
use App\Support\SectionClasses;

class Contact extends Block

{
	public $name = 'Kontakt';
	public $description = 'Contact';
	public $slug = 'contact';
	public $category = 'formatting';
	public $icon = 'email';
	public $keywords = ['formularz', 'kontakt'];
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
		$contact = new FieldsBuilder('contact');

		$contact
			->setLocation('block', '==', 'acf/contact') // ważne!
			/*--- FIELDS ---*/
			->addText('block-title', [
				'label' => 'Tytuł',
				'required' => 0,
			])
			->addAccordion('accordion1', [
				'label' => 'Kontakt',
				'open' => false,
				'multi_expand' => true,
			])
			/*--- TAB #1 ---*/
			->addTab('Dane', ['placement' => 'top'])
			->addGroup('g_contact_1', ['label' => ''])
			->addText('header', ['label' => 'Tytuł'])
			->addText('phone', [
				'label' => 'Numer telefonu',
			])
			->addText('mail', [
				'label' => 'Adres e-mail',
			])
			->addTextarea('address1', [
				'label' => 'Adres #1',
				'rows' => 3,
				'new_lines' => 'br',
			])
			->addTextarea('address2', [
				'label' => 'Adres #2',
				'rows' => 3,
				'new_lines' => 'br',
			])
			->addTextarea('hours', [
				'label' => 'Godziny pracy recepcji',
				'rows' => 2,
				'new_lines' => 'br',
			])
			->addImage('image', [
				'label' => 'Obraz w tle',
				'return_format' => 'array',
				'preview_size' => 'thumbnail',
			])
			->endGroup()
			/*--- TAB #2 ---*/
			->addTab('Formularz', ['placement' => 'top'])
			->addGroup('g_contact_2', ['label' => ''])
			->addText('title', ['label' => 'Tytuł'])
			->addText('shortcode', [
				'label' => 'Kod formularza',
				'instructions' => 'Wklej kod formularza:  [contact-form-7 id="f12c470" title="Contact form 1"]',
				'default_value' => '[contact-form-7 id="f12c470" title="Contact form 1"]',
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


		return $contact;
	}

	public function with(): array
	{
		$fields = [
			'g_contact_1' => get_field('g_contact_1'),
			'g_contact_2' => get_field('g_contact_2'),

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
