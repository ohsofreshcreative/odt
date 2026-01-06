<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class HeroAbout extends Block
{
	public $name = 'Hero - O nas';
	public $description = 'hero-about';
	public $slug = 'hero-about';
	public $category = 'formatting';
	public $icon = 'align-full-width';
	public $keywords = ['tresc', 'zdjecie'];
	public $mode = 'edit';
	public $supports = [
		'align' => false,
		'mode' => false,
		'jsx' => true,
	];

	public function fields()
	{
		$hero_about = new FieldsBuilder('hero-about');

		$hero_about
			->setLocation('block', '==', 'acf/hero-about') // ważne!
			->addText('block-title', [
				'label' => 'Tytuł',
				'required' => 0,
			])
			->addAccordion('accordion1', [
				'label' => 'Hero - O nas',
				'open' => false,
				'multi_expand' => true,
			])
			/*--- TAB #1 ---*/
			->addTab('Treść', ['placement' => 'top'])
			->addGroup('g_heroabout', ['label' => 'Hero - Pojedyncza oferta'])
			->addImage('image', [
				'label' => 'Obraz #1',
				'return_format' => 'array', 
				'preview_size' => 'thumbnail',
			])
			->addImage('bg', [
				'label' => 'Obraz w tle',
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
			->endGroup()

			/*--- TAB #2 ---*/
			->addTab('Liczby', ['placement' => 'top'])
			->addGroup('g_heroabout_2', ['label' => 'Hero - Pojedyncza oferta'])
			->addWysiwyg('txt1', [
				'label' => 'Liczba #1',
				'tabs' => 'all', 
				'toolbar' => 'full', 
				'media_upload' => true,
			])
			->addWysiwyg('txt2', [
				'label' => 'Liczba #2',
				'tabs' => 'all', 
				'toolbar' => 'full', 
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
			->addTrueFalse('nomt', [
				'label' => 'Usunięcie marginesu górnego',
				'ui' => 1,
				'ui_on_text' => 'Tak',
				'ui_off_text' => 'Nie',
			]);

		return $hero_about;
	}

	public function with()
	{
		return [
			'g_heroabout' => get_field('g_heroabout'),
			'g_heroabout_2' => get_field('g_heroabout_2'),
			'section_id' => get_field('section_id'),
			'section_class' => get_field('section_class'),
			'nomt' => get_field('nomt'),
		];
	}
}
