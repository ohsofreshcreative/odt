<?php

namespace App\Walkers;

class MegaMenuWalker extends \Walker_Nav_Menu
{
    private $is_megamenu = false;
    private $level_2_items_buffer = '';
    private $level_3_items_buffer = [];

    /**
     * Starts the list before the elements are added.
     */
    public function start_lvl(&$output, $depth = 0, $args = null)
    {
        // Jeśli to jest początek podmenu dla elementu z mega menu
        if ($depth === 0 && $this->is_megamenu) {
            $output .= '<ul class="dropdown-menu megamenu megamenu-content megamenu-initialized">';
            // Nie dodajemy nic więcej, bo całą strukturę zbudujemy w end_lvl
        } else {
            // Standardowe zachowanie dla zwykłych podmenu
            $indent = str_repeat("\t", $depth);
            $output .= "\n$indent<ul class=\"sub-menu\">\n";
        }
    }

    /**
     * Ends the list of after the elements are added.
     */
     public function end_lvl(&$output, $depth = 0, $args = null)
    {
        // Jeśli to jest koniec podmenu dla elementu z mega menu
        if ($depth === 0 && $this->is_megamenu) {
            // Kolumna z elementami poziomu 2
            $output .= '<div class="flex"><div class="level-2-column"><ul>' . $this->level_2_items_buffer . '</ul></div>';

            // Kolumna z elementami poziomu 3 i obrazkiem
            $output .= '<div class="level-3-column">';
            foreach ($this->level_3_items_buffer as $parent_id => $data) {
               $output .= '<ul class="level-3-list" data-parent-id="menu-item-' . $parent_id . '">' . $data['items'] . '</ul>';
            }
            // Miejsce na obrazek aktywnego elementu poziomu 2
            $output .= '<div class="active-level-2-image"></div>';
            $output .= '</div></div>'; // .level-3-column

            // --- POCZĄTEK: Dodanie CTA z Options Page ---
            if (function_exists('get_field') && get_field('megamenu_cta_enabled', 'option')) {
                $cta_image = get_field('megamenu_cta_image', 'option');
                $cta_header = get_field('megamenu_cta_header', 'option');
                $cta_text = get_field('megamenu_cta_text', 'option');
                $cta_button = get_field('megamenu_cta_button', 'option');

                $bg_style = '';
                if ($cta_image) {
                    $bg_style = 'style="background-image: linear-gradient(rgba(19,42,35,0.7), rgba(13, 63, 47,0.7)), url(' . esc_url($cta_image['url']) . '); background-size: cover; background-position: center;"';
                }

                $output .= '<div class="megamenu-cta-wrapper">';
                $output .= '<div class="megamenu-cta__wrapper py-8 px-12 rounded-b-2xl" ' . $bg_style . '>';
                $output .= '<div class="megamenu-cta__inside grid grid-cols-1 md:grid-cols-[2fr_1fr] items-center gap-6">';
                $output .= '<div class="megamenu-cta__content">';

                if ($cta_header) {
                    $output .= '<h5 class="text-white">' . esc_html($cta_header) . '</h5>';
                }
                if ($cta_text) {
                    $output .= '<div class="text-secondary text-lg mt-1">' . $cta_text . '</div>';
                }

                $output .= '</div>'; // .__content

                if ($cta_button && !empty($cta_button['url']) && !empty($cta_button['title'])) {
                    $target = !empty($cta_button['target']) ? $cta_button['target'] : '_self';
                    $output .= '<a class="second-btn h-max justify-self-start md:justify-self-end" href="' . esc_url($cta_button['url']) . '" target="' . esc_attr($target) . '">' . esc_html($cta_button['title']) . '</a>';
                }

                $output .= '</div>'; // .__inside
                $output .= '</div>'; // .__wrapper
                $output .= '</div>'; // .megamenu-cta-wrapper
            }
            // --- KONIEC: Dodanie CTA ---

            $output .= '</ul>'; // .dropdown-menu

            // Resetujemy stan po zakończeniu mega menu
            $this->is_megamenu = false;
            $this->level_2_items_buffer = '';
            $this->level_3_items_buffer = [];
        } else {
            // Standardowe zachowanie
            $indent = str_repeat("\t", $depth);
            $output .= "$indent</ul>\n";
        }
    }

    /**
     * Starts the element output.
     */
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
    {
        // Sprawdzamy, czy element nadrzędny ma klasę 'has-megamenu'
        if ($depth === 0 && in_array('has-megamenu', $item->classes)) {
            $this->is_megamenu = true;
        }

        // Jeśli nie jesteśmy w mega menu, użyj domyślnego zachowania
        if (!$this->is_megamenu) {
            parent::start_el($output, $item, $depth, $args, $id);
            return;
        }

        // --- Logika dla Mega Menu ---

        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        // Budowanie klas
        $classes = empty($item->classes) ? [] : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        $classes[] = 'level-' . ($depth + 1) . '-item'; // level-1-item, level-2-item, etc.

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $item_id_attr = ' id="menu-item-' . $item->ID . '"';

        // Budowanie atrybutów linku
        $atts = [];
        $atts['title']  = ! empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = ! empty($item->target)     ? $item->target     : '';
        $atts['rel']    = ! empty($item->xfn)        ? $item->xfn        : '';
        $atts['href']   = ! empty($item->url)        ? $item->url        : '';
        $atts['class'] = 'level-' . ($depth + 1) . '-link'; // level-1-link, level-2-link, etc.

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $title = apply_filters('the_title', $item->title, $item->ID);
        $item_output = $args->before . '<a' . $attributes . '>' . $args->link_before . $title . $args->link_after . '</a>' . $args->after;

        // Generowanie HTML w zależności od poziomu
        $li_attributes = '';
        if ($depth === 1) { // Poziom 2 (np. Teczki Archiwizacyjne)
            $image_src = get_post_meta($item->ID, '_menu_item_image_src', true);
            if (!empty($image_src)) {
                $li_attributes .= ' data-image-src="' . esc_attr($image_src) . '"';
            }
            $this->level_2_items_buffer .= $indent . '<li' . $item_id_attr . $class_names . $li_attributes . '>';
            $this->level_2_items_buffer .= $item_output;
            // Nie zamykamy <li> tutaj, zrobimy to w end_el
        } elseif ($depth === 2) { // Poziom 3 (np. Teczki na akta osobowe)
            // --- POCZĄTEK ZMIANY ---
            // Sprawdź, czy jest opis i dodaj go do linku
            if (!empty($item->description)) {
                $item_output .= '<span class="menu-item-description w-3/4">' . esc_html($item->description) . '</span>';
            }
            // --- KONIEC ZMIANY ---

            $parent_id = $item->menu_item_parent;
            if (!isset($this->level_3_items_buffer[$parent_id])) {
                $this->level_3_items_buffer[$parent_id] = ['items' => ''];
            }
            $this->level_3_items_buffer[$parent_id]['items'] .= $indent . '<li' . $item_id_attr . $class_names . '>' . $item_output . '</li>';
        } else { // Poziom 1 (np. Oferta)
            $output .= $indent . '<li' . $item_id_attr . $class_names . '>';
            $output .= $item_output;
        }
    }

    /**
     * Ends the element output.
     */
    public function end_el(&$output, $item, $depth = 0, $args = null)
    {
        if (!$this->is_megamenu) {
            parent::end_el($output, $item, $depth, $args);
            return;
        }

        if ($depth === 1) {
            $this->level_2_items_buffer .= "</li>\n";
        } elseif ($depth === 0) {
            $output .= "</li>\n";
        }
        // Dla depth === 2 nie robimy nic, bo <li> jest już zamknięte w start_el
    }
}