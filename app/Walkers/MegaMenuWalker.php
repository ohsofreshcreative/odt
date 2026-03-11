<?php

namespace App\Walkers;

class MegaMenuWalker extends \Walker_Nav_Menu
{
    private $is_megamenu = false;
    private $megamenu_item_id = 0;
    private $level_2_items_buffer = '';
    private $level_3_items_buffer = [];

    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
    {
        // Na najwyższym poziomie sprawdzamy, czy to początek mega menu
        if ($depth === 0) {
            $this->is_megamenu = in_array('has-megamenu', $item->classes);
            if ($this->is_megamenu) {
                $this->megamenu_item_id = $item->ID;
            }
        }

        // Jeśli jesteśmy wewnątrz mega menu, użyj niestandardowej logiki
        if ($this->is_megamenu && $item->menu_item_parent == $this->megamenu_item_id) {
            $this->handle_megamenu_level_2($output, $item, $depth, $args);
        } elseif ($this->is_megamenu && in_array($item->menu_item_parent, array_keys($this->level_3_items_buffer))) {
             $this->handle_megamenu_level_3($output, $item, $depth, $args);
        } else {
            // W przeciwnym razie (zwykłe menu lub element najwyższego poziomu), użyj domyślnego walkera
            parent::start_el($output, $item, $depth, $args, $id);
        }
    }

    public function end_el(&$output, $item, $depth = 0, $args = null)
    {
        // Jeśli to jest element zamykający mega menu, nie używaj logiki rodzica
        if ($this->is_megamenu && ($depth === 1 || $depth === 2)) {
             if ($depth === 1) {
                $this->level_2_items_buffer .= "</li>\n";
            }
        } else {
            parent::end_el($output, $item, $depth, $args);
        }
    }

    public function start_lvl(&$output, $depth = 0, $args = null)
    {
        if ($depth === 0 && $this->is_megamenu) {
            $output .= '<ul class="dropdown-menu megamenu megamenu-content megamenu-initialized">';
        } else {
            parent::start_lvl($output, $depth, $args);
        }
    }

    public function end_lvl(&$output, $depth = 0, $args = null)
    {
        if ($depth === 0 && $this->is_megamenu) {
            // Zbuduj strukturę HTML dla mega menu
            $output .= '<div class="flex"><div class="level-2-column"><ul>' . $this->level_2_items_buffer . '</ul></div>';
            $output .= '<div class="level-3-column">';
            foreach ($this->level_3_items_buffer as $parent_id => $data) {
                $output .= '<ul class="level-3-list" data-parent-id="menu-item-' . $parent_id . '">' . $data['items'] . '</ul>';
            }
            $output .= '<div class="active-level-2-image"></div></div></div>';

            // Dodaj CTA, jeśli istnieje
            $this->add_cta($output);

            $output .= '</ul>';

            // Zresetuj stan po zakończeniu
            $this->is_megamenu = false;
            $this->megamenu_item_id = 0;
            $this->level_2_items_buffer = '';
            $this->level_3_items_buffer = [];
        } else {
            parent::end_lvl($output, $depth, $args);
        }
    }

    private function handle_megamenu_level_2(&$output, $item, $depth, $args) {
        $item_id_attr = ' id="menu-item-' . $item->ID . '"';
        $class_names = ' class="' . implode(' ', $item->classes) . ' level-2-item"';
        $image_src = get_post_meta($item->ID, '_menu_item_image_src', true);
        $li_attributes = $image_src ? ' data-image-src="' . esc_attr($image_src) . '"' : '';

        $atts = [
            'title'  => $item->attr_title, 'target' => $item->target, 'rel' => $item->xfn, 'href' => $item->url,
            'class' => 'level-2-link'
        ];
        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) $attributes .= ' ' . $attr . '="' . esc_attr($value) . '"';
        }
        $title = apply_filters('the_title', $item->title, $item->ID);
        $item_output = $args->before . '<a' . $attributes . '>' . $args->link_before . $title . $args->link_after . '</a>' . $args->after;

        $this->level_2_items_buffer .= '<li' . $item_id_attr . $class_names . $li_attributes . '>' . $item_output;
        $this->level_3_items_buffer[$item->ID] = ['items' => '']; // Przygotuj bufor na dzieci
    }

    private function handle_megamenu_level_3(&$output, $item, $depth, $args) {
        $parent_id = $item->menu_item_parent;
        $item_id_attr = ' id="menu-item-' . $item->ID . '"';
        $class_names = ' class="' . implode(' ', $item->classes) . ' level-3-item"';

        $atts = [
            'title'  => $item->attr_title, 'target' => $item->target, 'rel' => $item->xfn, 'href' => $item->url,
            'class' => 'level-3-link'
        ];
        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) $attributes .= ' ' . $attr . '="' . esc_attr($value) . '"';
        }
        $title = apply_filters('the_title', $item->title, $item->ID);
        $item_output = $args->before . '<a' . $attributes . '>' . $args->link_before . $title . $args->link_after . '</a>' . $args->after;

        if (!empty($item->description)) {
            $item_output .= '<span class="menu-item-description w-3/4">' . esc_html($item->description) . '</span>';
        }

        if (isset($this->level_3_items_buffer[$parent_id])) {
            $this->level_3_items_buffer[$parent_id]['items'] .= '<li' . $item_id_attr . $class_names . '>' . $item_output . '</li>';
        }
    }

    private function add_cta(&$output) {
        if (function_exists('get_field') && get_field('megamenu_cta_enabled', 'option')) {
            $cta_image = get_field('megamenu_cta_image', 'option');
            $cta_header = get_field('megamenu_cta_header', 'option');
            $cta_text = get_field('megamenu_cta_text', 'option');
            $cta_button = get_field('megamenu_cta_button', 'option');
            $bg_style = $cta_image ? 'style="background-image: linear-gradient(rgba(19,42,35,0.7), rgba(13, 63, 47,0.7)), url(' . esc_url($cta_image['url']) . '); background-size: cover; background-position: center;"' : '';

            $output .= '<div class="megamenu-cta-wrapper"><div class="megamenu-cta__wrapper py-8 px-12 rounded-b-2xl" ' . $bg_style . '><div class="megamenu-cta__inside grid grid-cols-1 md:grid-cols-[2fr_1fr] items-center gap-6"><div class="megamenu-cta__content">';
            if ($cta_header) $output .= '<h5 class="text-white">' . esc_html($cta_header) . '</h5>';
            if ($cta_text) $output .= '<div class="text-secondary text-lg mt-1">' . $cta_text . '</div>';
            $output .= '</div>';
            if ($cta_button && !empty($cta_button['url']) && !empty($cta_button['title'])) {
                $target = !empty($cta_button['target']) ? $cta_button['target'] : '_self';
                $output .= '<a class="second-btn h-max justify-self-start md:justify-self-end" href="' . esc_url($cta_button['url']) . '" target="' . esc_attr($target) . '">' . esc_html($cta_button['title']) . '</a>';
            }
            $output .= '</div></div></div>';
        }
    }
}