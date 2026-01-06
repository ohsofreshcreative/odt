<?php

namespace App\Fields;

use Log1x\AcfComposer\Field;
use StoutLogic\AcfBuilder\FieldsBuilder;

class PlacesFooter extends Field
{
	public $name = 'Gabinety - Stopka';
	public $description = 'places-footer';
	public $slug = 'places-footer';
	public $category = 'formatting';
	public $icon = 'admin-site';
	public $keywords = ['places', 'footer', 'gabinety'];
	public $mode = 'edit';
	public $supports = [
		'align' => false,
		'mode' => false,
		'jsx' => true,
		'anchor' => true,
		'customClassName' => true,
	];

	public function fields(): array
	{
		$places_footer = new FieldsBuilder('places_footer');

		$places_footer
			->setLocation('options_page', '==', 'places-footer')

			/*--- REPEATER ---*/
			->addRepeater('g_places_footer', ['label' => ''])
			->addText('title', ['label' => 'Nazwa'])
			->addText('address', ['label' => 'Adres'])
			->addLink('button', [
				'label' => 'Przycisk',
				'return_format' => 'array',
			])
			->endRepeater();

		return [$places_footer];
	}
}
