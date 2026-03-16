@php
$sectionClass = '';
$sectionClass .= $flip ? ' order-flip' : '';
@endphp

<!--- product --->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-product relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main">

		<div class="__item bg-white p-8 radius">
			@if(!empty($g_product['image']['url']))
			<img class="m-img" src="{{ $g_product['image']['url'] }}" alt="{{ $g_product['image']['alt'] ?? '' }}">
			@endif
			<h3 class="text-primary">{{ $g_product['title'] }}</h3>

			@if(!empty($g_product['product_data']['short_description']))
			<div class="short-description border-b border-primary-300 pb-4 mt-2">{!! $g_product['product_data']['short_description'] !!}</div>
			@endif

			@if(!empty($g_product['product_data']['price_html']))
			<h3 class="__price text-secondary font-header mt-4">{!! $g_product['product_data']['price_html'] !!}</h3>
			@endif

			@if (!empty($g_product['product']->ID))
			@if ($g_product['button_type'] === 'product_link' && !empty($g_product['product_data']['permalink']))
			<x-button
				:href="$g_product['product_data']['permalink']"
				variant="primary"
				class="mt-2">
				{{ $g_product['btn'] ?: 'Zobacz produkt' }}
			</x-button>
			@elseif ($g_product['button_type'] === 'add_to_cart' && !empty($g_product['product_data']['add_to_cart_url']))
			<x-button
				:href="wc_get_cart_url() . '?add-to-cart=' . $g_product['product']->ID"
				variant="primary"
				class="mt-2 add_to_cart_button">
				{{ $g_product['btn'] ?: 'Dodaj do koszyka' }}
			</x-button>
			@endif
			@endif

			@if(!empty($g_product['product_data']['description']))
			<div class="__desc mt-6">{!! $g_product['product_data']['description'] !!}</div>
			@endif
		</div>
	</div>

</section>