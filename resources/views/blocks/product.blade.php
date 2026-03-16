@php
$sectionClass = '';
$sectionClass .= $flip ? ' order-flip' : '';
@endphp

<!--- triple --->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-triple relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main grid grid-cols-1 lg:grid-cols-3 gap-16 lg:gap-8">
	
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

			@if (!empty($g_product['product_data']['add_to_cart_url']) && !empty($g_product['product']->ID))
			<x-button
				:href="wc_get_cart_url() . '?add-to-cart=' . $g_product['product']->ID"
				variant="primary"
				class="mt-2 add_to_cart_button">
				Kup teraz
			</x-button>
			@endif

			@if(!empty($g_product['product_data']['description']))
			<div class="__desc mt-6">{!! $g_product['product_data']['description'] !!}</div>
			@endif
		</div>
	</div>

</section>