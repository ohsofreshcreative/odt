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
		@foreach(['col1', 'col2', 'col3'] as $col_key)
		@php $col = ${$col_key}; @endphp
		@if(!empty($col['title']))
		<div class="__item bg-white p-8 radius">
			@if(!empty($col['image']['url']))
			<img class="m-img" src="{{ $col['image']['url'] }}" alt="{{ $col['image']['alt'] ?? '' }}">
			@endif
			<h3 class="text-primary">{{ $col['title'] }}</h3>

			@if(!empty($col['product_data']['short_description']))
			<div class="short-description border-b border-primary-300 pb-4 mt-2">{!! $col['product_data']['short_description'] !!}</div>
			@endif

			@if(!empty($col['product_data']['price_html']))
			<h3 class="__price text-secondary font-header mt-4">{!! $col['product_data']['price_html'] !!}</h3>
			@endif

			@if (!empty($col['product']->ID))
			@if ($col['button_type'] === 'product_link' && !empty($col['product_data']['permalink']))
			<x-button
				:href="$col['product_data']['permalink']"
				variant="primary"
				class="mt-2">
				{{ $col['btn'] }}
			</x-button>
			@elseif (!empty($col['product_data']['add_to_cart_url']))
			<x-button
				:href="wc_get_cart_url() . '?add-to-cart=' . $col['product']->ID"
				variant="primary"
				class="mt-2 add_to_cart_button">
				{{ $col['btn'] }}
			</x-button>
			@endif
			@endif

			@if(!empty($col['product_data']['description']))
			<div class="__desc mt-6">{!! $col['product_data']['description'] !!}</div>
			@endif
			@if(!empty($col['when']))
			<div class="__when mt-10">
				<p class="text-gray-300">Kiedy warto?</p>
				<div class="__content mt-2 @if($nolist) nolist @endif">{!! $col['when'] !!}</div>
			</div>
			@endif
		</div>
		@endif
		@endforeach
	</div>

</section>