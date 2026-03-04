@extends('layouts.app')

@section('content')
  @php
    do_action('get_header', 'shop');
    do_action('woocommerce_before_main_content');
  @endphp

  <header data-gsap-anim="section" class="woocommerce-products-header c-main">
    @if (apply_filters('woocommerce_show_page_title', true))
      <h1 data-gsap-element="header" class="woocommerce-products-header__title page-title mt-10 mb-6">{!! woocommerce_page_title(false) !!}</h1>
    @endif
  </header>

  <div class="c-main">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 lg:gap-12">
      
      {{-- Sidebar z filtrami --}}
      <aside class="lg:col-span-1">
        <h3 class="text-lg font-semibold mb-4">Kategorie</h3>
        @php
          $product_categories = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => true]);
        @endphp
        @if (!is_wp_error($product_categories) && !empty($product_categories))
          <ul id="product-cat-filters" class="space-y-2">
            <li>
              <a href="{{ get_permalink(wc_get_page_id('shop')) }}" data-category="all" class="filter-link active font-bold">Wszystkie</a>
            </li>
            @foreach ($product_categories as $category)
              <li>
                <a href="{{ get_term_link($category) }}" data-category="{{ $category->slug }}" class="filter-link">{{ $category->name }}</a>
              </li>
            @endforeach
          </ul>
        @endif
      </aside>

   <div 
        id="ajax-product-archive" 
        class="lg:col-span-3"
        data-nonce="{{ wp_create_nonce('product_archive_nonce') }}"
        data-ajax-url="{{ admin_url('admin-ajax.php') }}"
        data-current-category="{{ get_query_var('product_cat') ?: 'all' }}"
      >
        <section data-gsap-anim="section">
          <div class="">
            @php
              do_action('woocommerce_archive_description');
            @endphp

            @if (woocommerce_product_loop())
              @php
                do_action('woocommerce_before_shop_loop');
                woocommerce_product_loop_start();
              @endphp

              @if (wc_get_loop_prop('total'))
                @while (have_posts())
                  @php
                    the_post();
                    do_action('woocommerce_shop_loop');
                    wc_get_template_part('content', 'product');
                  @endphp
                @endwhile
              @endif

              @php
                woocommerce_product_loop_end();
                // Przywracamy standardową paginację WooCommerce
                do_action('woocommerce_after_shop_loop');
              @endphp
            @else
              @php
                do_action('woocommerce_no_products_found');
              @endphp
            @endif
          </div>
        </section>
      </div>
    </div>
  </div>

  <!--- DESCRIPTION --->
  @php
    $term = get_queried_object();
  @endphp

  @if ($term instanceof WP_Term && $term->taxonomy === 'product_cat')
    @php
      $term_id = $term->term_id;
      $acf_header = get_field('header', 'term_' . $term_id) ?: get_field('header', 'product_cat_' . $term_id);
      $term_desc = term_description($term_id, 'product_cat');
    @endphp

    @if (!empty($acf_header) || !empty($term_desc))
      <section class="shop-term-intro">
        @if (!empty($acf_header))
          <h4 class="shop-term-heading">{{ $acf_header }}</h4>
        @endif

        @if (!empty($term_desc))
          <div class="shop-term-description">{!! $term_desc !!}</div>
        @endif
      </section>
    @endif
  @endif
  <!--- DESCRIPTION END --->

  @php
    do_action('woocommerce_after_main_content');
    do_action('get_sidebar', 'shop');
    do_action('get_footer', 'shop');
  @endphp
@endsection