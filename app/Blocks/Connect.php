<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class Connect extends Block
{
	public $name = 'Kontakt - Stopka';
	public $description = 'connect';
	public $slug = 'connect'; 
	public $category = 'formatting';
	public $icon = 'email';
	public $keywords = ['offer', 'cards', 'oferta', 'kafelki'];
	public $mode = 'edit';
	public $supports = [
		'align' => false,
		'mode' => false,
		'jsx' => true,
		'multiple' => true,
		'anchor' => true,
		'customClassName' => true,
	];

	/**
	 * The block field group.
	 *
	 * @return array
	 */
	public function fields()
	{
		$bottomBlock = new FieldsBuilder('connect');

		$bottomBlock
			->addText('block-title', [
				'label' => 'Tytuł',
				'required' => 0,
			])
			->addAccordion('accordion1', [
				'label' => 'Kontakt - Stopka',
				'open' => false,
				'multi_expand' => true,
			])
			->addTab('Elementy', ['placement' => 'top'])
			->addMessage('Edycja', 'Pole edytujemy klikajac w menu panelu administratora "Kontakt - Stopka".')
			
			/*--- USTAWIENIA BLOKU ---*/

			->addTab('Ustawienia bloku', ['placement' => 'top'])
			->addText('section_id', [
				'label' => 'ID',
			])
			->addText('section_class', [
				'label' => 'Dodatkowe klasy CSS',
			])
			->addTrueFalse('nomt', [
				'label' => 'Usunięcie marginesu górnego',
				'ui' => 1,
				'ui_on_text' => 'Tak',
				'ui_off_text' => 'Nie',
			]);

		return $bottomBlock->build();
	}

	/**
	 * Data to be passed to the block before rendering.
	 *
	 * @return array
	 */
	public function with()
	{
		return [
			'bottom' => get_field('bottom', 'option'),
			'section_id' => get_field('section_id'),
			'section_class' => get_field('section_class'),
			'nomt' => get_field('nomt'),
		];
	}
}
