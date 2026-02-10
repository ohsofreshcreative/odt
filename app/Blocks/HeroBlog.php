<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class HeroBlog extends Block
{
	public $name = 'Hero - Baza wiedzy';
	public $description = 'hero-blog';
	public $slug = 'hero-blog';
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
		$hero_blog = new FieldsBuilder('hero-blog');

		$hero_blog
			->setLocation('block', '==', 'acf/hero-blog') // ważne!
			->addText('block-title', [
				'label' => 'Tytuł',
				'required' => 0,
			])
			->addAccordion('accordion1', [
				'label' => 'Hero - Baza wiedzy',
				'open' => false,
				'multi_expand' => true,
			])
			->addTab('Treść', ['placement' => 'top'])
			->addGroup('g_hero_blog', ['label' => 'Hero - Produkt'])
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

			->addTab('Ustawienia bloku', ['placement' => 'top'])

			->addTrueFalse('flip', [
				'label' => 'Odwrotna kolejność',
				'ui' => 1,
				'ui_on_text' => 'Tak',
				'ui_off_text' => 'Nie',
			])
			->addTrueFalse('gfx_top', [
				'label' => 'Grafika górna',
				'ui' => 1,
				'ui_on_text' => 'Tak',
				'ui_off_text' => 'Nie',
			])
			->addTrueFalse('gfx_bottom', [
				'label' => 'Grafika dolna',
				'ui' => 1,
				'ui_on_text' => 'Tak',
				'ui_off_text' => 'Nie',
			]);

		return $hero_blog;
	}

	public function with()
	{
		return [
			'g_hero_blog' => get_field('g_hero_blog'),
			'section_id' => get_field('section_id'),
			'section_class' => get_field('section_class'),
			'flip' => get_field('flip'),
			'wide' => get_field('wide'),
			'nomt' => get_field('nomt'),
			'gap' => get_field('gap'),
			'lightbg' => get_field('lightbg'),
			'graybg' => get_field('graybg'),
			'whitebg' => get_field('whitebg'),
			'brandbg' => get_field('brandbg'),
		];
	}
}
