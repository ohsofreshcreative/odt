@extends('layouts.app')

@section('content')
  @php
    do_action('get_header', 'shop');
    do_action('woocommerce_before_main_content');

    $shop_page_id = get_option('woocommerce_shop_page_id');
  @endphp

  @if ($shop_page_id)
    @php
      $shop_page = get_post($shop_page_id);
      setup_postdata($shop_page);
    @endphp

    <div class="max-w-none">
      {!! apply_filters('the_content', $shop_page->post_content) !!}
    </div>

    @php
      wp_reset_postdata();
    @endphp
  @else
   
    <div class="c-main">
      <div class="alert alert-warning">
      </div>
    </div>
  @endif

  @php
    do_action('woocommerce_after_main_content');
    do_action('get_sidebar', 'shop');
    do_action('get_footer', 'shop');
  @endphp
@endsection