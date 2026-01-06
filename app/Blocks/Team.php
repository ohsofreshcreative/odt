<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class Team extends Block
{
	public $name = 'Nasz zespół';
	public $description = 'team';
	public $slug = 'team';
	public $category = 'formatting';
	public $icon = 'admin-users';
	public $keywords = ['team', 'nasz', 'zespol', 'kafelki', 'slider', 'eksperci'];
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
		$team = new FieldsBuilder('team');

		$team
			->setLocation('block', '==', 'acf/team')
			->addText('block-title', [
				'label' => 'Tytuł',
				'required' => 0,
			])
			->addAccordion('accordion1', [
				'label' => 'Nasz zespół',
				'open' => false,
				'multi_expand' => true,
			])
			/*--- FIELDS ---*/
			->addTab('Treści', ['placement' => 'top'])
			->addGroup('g_team', ['label' => ''])
			->addText('header', ['label' => 'Nagłówek'])
			->addWysiwyg('content', [
				'label' => 'Treść',
				'tabs' => 'all',
				'toolbar' => 'full',
				'media_upload' => true,
			])
			->addImage('image', [
				'label' => 'Obraz w tle',
				'return_format' => 'array',
				'preview_size' => 'thumbnail',
			])
			->endGroup()

			->addTaxonomy('team_categories', [
				'label'        => 'Filtruj zespół po kategoriach',
				'taxonomy'     => 'category',
				'field_type'   => 'checkbox',
				'return_format' => 'id',
				'multiple'     => 1,
				'add_term'     => 0,
				'load_terms'   => 0, // wyłączamy na początek
				'save_terms'   => 0, // jawnie wyłączone
				'allow_null'   => 1,
			])

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

		return $team;
	}

	public function with()
	{
		return [
			'team_posts' => $this->team_posts(),

			'g_team' => get_field('g_team'),
			'team_categories'   => get_field('team_categories'),
			'section_id' => get_field('section_id'),
			'section_class' => get_field('section_class'),
			'nolist' => get_field('nolist'),
			'flip' => get_field('flip'),
			'wide' => get_field('wide'),
			'nomt' => get_field('nomt'),
			'gap' => get_field('gap'),
			'background' => get_field('background'),
		];
	}

	public function team_posts()
	{
		$selected_categories = get_field('team_categories');

		error_log('TEAM BLOCK selected_categories: ' . print_r($selected_categories, true));

		$args = [
			'post_type'      => 'team',
			'posts_per_page' => -1,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'post_status'    => 'publish',
		];

		if (!empty($selected_categories)) {
			$args['tax_query'] = [
				[
					'taxonomy' => 'category',
					'field'    => 'term_id',
					'terms'    => $selected_categories,
				],
			];
		}

		return get_posts($args);
	}
}
