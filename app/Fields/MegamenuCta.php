<?php

namespace App\Fields;

use Log1x\AcfComposer\Field;
use StoutLogic\AcfBuilder\FieldsBuilder;

class MegamenuCta extends Field
{
    /**
     * The field group.
     *
     * @return array
     */
    public function fields(): array
    {
        $megamenuCta = new FieldsBuilder('megamenu_cta', [
            'title' => 'CTA w Megamenu',
        ]);

        $megamenuCta
            ->setLocation('options_page', '==', 'megamenu-settings')
            ->addTrueFalse('megamenu_cta_enabled', [
                'label' => 'Włącz CTA w Megamenu',
                'instructions' => 'Zaznacz, aby wyświetlić sekcję CTA na dole megamenu.',
                'ui' => 1,
            ])
            ->addImage('megamenu_cta_image', [
                'label' => 'Obrazek tła',
                'return_format' => 'array',
                'conditional_logic' => [
                    [
                        [
                            'field' => 'megamenu_cta_enabled',
                            'operator' => '==',
                            'value' => '1',
                        ],
                    ],
                ],
            ])
            ->addText('megamenu_cta_header', [
                'label' => 'Nagłówek',
                'conditional_logic' => [
                    [
                        [
                            'field' => 'megamenu_cta_enabled',
                            'operator' => '==',
                            'value' => '1',
                        ],
                    ],
                ],
            ])
            ->addWysiwyg('megamenu_cta_text', [
                'label' => 'Tekst',
                'tabs' => 'visual',
                'media_upload' => 0,
                'toolbar' => 'basic',
                'conditional_logic' => [
                    [
                        [
                            'field' => 'megamenu_cta_enabled',
                            'operator' => '==',
                            'value' => '1',
                        ],
                    ],
                ],
            ])
            ->addLink('megamenu_cta_button', [
                'label' => 'Przycisk',
                'conditional_logic' => [
                    [
                        [
                            'field' => 'megamenu_cta_enabled',
                            'operator' => '==',
                            'value' => '1',
                        ],
                    ],
                ],
            ]);

        return $megamenuCta->build();
    }
}