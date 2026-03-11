<?php

namespace App\Walkers;

class MegaMenuWalker extends \Walker_Nav_Menu
{
    private $is_megamenu = false;
    private $level_2_items_buffer = '';
    private $level_3_items_buffer = [];

    public function start_lvl(&$output, $depth = 0, $args = null)
    {
        if ($depth === 0 && $this->is_megamenu) {
            $output .= '<ul class="dropdown-menu megamenu megamenu-content megamenu-initialized">';
        } else {
            // Dla zwykłego dropdown, dodajemy klasę 'dropdown-menu'
            $indent = str_repeat("\t", $depth);
            $output .= "\n$indent<ul class=\"sub-menu dropdown-menu\">\n";
        }
    }

    public function end_lvl(&$output, $depth = 0, $args = null)
    {
        if ($depth === 0 && $this->is_megamenu) {
            // --- Budowa Mega Menu ---
            $output .= '<div class="flex"><div class="level-2-column"><ul>' . $this->level_2_items_buffer . '</ul></div>';
            $output .= '<div class="level-3-column">';
            foreach ($this->level_3_items_buffer as $parent_id => $data) {
                $output .= '<ul class="level-3-list" data-parent-id="menu-item-' . $parent_id . '">' . $data['items'] . '</ul>';
            }
            $output .= '<div class="active-level-2-image"></div></div></div>';
            $this->add_cta($output);
            $output .= '</ul>';

            // Reset
            $this->is_megamenu = false;
            $this->level_2_items_buffer = '';
            $this->level_3_items_buffer = [];
        } else {
            $indent = str_repeat("\t", $depth);
            $output .= "$indent</ul>\n";
        }
    }

    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
    {
        if ($depth === 0) {
            $this->is_megamenu = in_array('has-megamenu', $item->classes);
        }

        if ($this->is_megamenu) {
            $this->handle_megamenu_el($output, $item, $depth, $args);
        } else {
            // --- NOWA LOGIKA DLA ZWYKŁEGO DROPDOWN ---
            $indent = ($depth) ? str_repeat("\t", $depth) : '';
            $classes = empty($item->classes) ? [] : (array) $item->classes;
            $classes[] = 'menu-item-' . $item->ID;

            // Dodajemy klasę 'dropdown' do elementu LI, który ma dzieci
            if (in_array('menu-item-has-children', $classes)) {
                $classes[] = 'dropdown';
            }

            $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
            $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
            $id_attr = ' id="menu-item-' . $item->ID . '"';
            $output .= $indent . '<li' . $id_attr . $class_names . '>';

            $atts = [
                'title'  => !empty($item->attr_title) ? $item->attr_title : '',
                'target' => !empty($item->target) ? $item->target : '',
                'rel'    => !empty($item->xfn) ? $item->xfn : '',
                'href'   => !empty($item->url) ? $item->url : '#', // Domyślnie '#' dla dropdown
                'class'  => 'nav-link', // Standardowa klasa linku
            ];

            // Dodajemy atrybuty data-* dla linku, który jest rodzicem dropdown
            if ($args->has_children) {
                $atts['class'] .= ' dropdown-toggle';
                $atts['data-bs-toggle'] = 'dropdown';
            }

            $attributes = '';
            foreach ($atts as $attr => $value) {
                if (!empty($value)) {
                    $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                    $attributes .= ' ' . $attr . '="' . $value . '"';
                }
            }

            $title = apply_filters('the_title', $item->title, $item->ID);
            $item_output = $args->before . '<a' . $attributes . '>' . $args->link_before . $title . $args->link_after . '</a>' . $args->after;
            $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
        }
    }

    public function end_el(&$output, $item, $depth = 0, $args = null)
    {
        if ($this->is_megamenu) {
            if ($depth === 1) {
                $this->level_2_items_buffer .= "</li>\n";
            } elseif ($depth === 0) {
                $output .= "</li>\n";
            }
        } else {
            $output .= "</li>\n";
        }
    }

    // --- Prywatne metody pomocnicze dla Mega Menu (bez zmian) ---
    private function handle_megamenu_el(&$output, $item, $depth, $args) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        $classes = empty($item->classes) ? [] : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        $classes[] = 'level-' . ($depth + 1) . '-item';
        $class_names = ' class="' . esc_attr(join(' ', $classes)) . '"';
        $item_id_attr = ' id="menu-item-' . $item->ID . '"';

        $atts = [
            'title' => $item->attr_title, 'target' => $item->target, 'rel' => $item->xfn, 'href' => $item->url,
            'class' => 'level-' . ($depth + 1) . '-link'
        ];
        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) $attributes .= ' ' . $attr . '="' . esc_attr($value) . '"';
        }
        $title = apply_filters('the_title', $item->title, $item->ID);
        $item_output = $args->before . '<a' . $attributes . '>' . $args->link_before . $title . $args->link_after . '</a>' . $args->after;

        if ($depth === 1) {
            $image_src = get_post_meta($item->ID, '_menu_item_image_src', true);
            $li_attributes = $image_src ? ' data-image-src="' . esc_attr($image_src) . '"' : '';
            $this->level_2_items_buffer .= $indent . '<li' . $item_id_attr . $class_names . $li_attributes . '>' . $item_output;
        } elseif ($depth === 2) {
            if (!empty($item->description)) {
                $item_output .= '<span class="menu-item-description w-3/4">' . esc_html($item->description) . '</span>';
            }
            $parent_id = $item->menu_item_parent;
            if (!isset($this->level_3_items_buffer[$parent_id])) $this->level_3_items_buffer[$parent_id] = ['items' => ''];
            $this->level_3_items_buffer[$parent_id]['items'] .= $indent . '<li' . $item_id_attr . $class_names . '>' . $item_output . '</li>';
        } else {
            $output .= $indent . '<li' . $item_id_attr . $class_names . '>' . $item_output;
        }
    }

    private function add_cta(&$output) {
        if (function_exists('get_field') && get_field('megamenu_cta_enabled', 'option')) {
            $cta_image = get_field('megamenu_cta_image', 'option');
            $cta_header = get_field('megamenu_cta_header', 'option');
            $cta_text = get_field('megamenu_cta_text', 'option');
            $cta_button = get_field('megamenu_cta_button', 'option');
            $bg_style = $cta_image ? 'style="background-image: linear-gradient(rgba(19,42,35,0.7), rgba(13, 63, 47,0.7)), url(' . esc_url($cta_image['url']) . '); background-size: cover; background-position: center;"' : '' : '';
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