<!--- voucher -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-voucher relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main relative">
		<div class="__col grid grid-cols-1 lg:grid-cols-2 items-center gap-8 lg:gap-20">
			@if (!empty($g_voucher['image']))
			<div data-gsap-element="img" class="__img h-full order1">
				<img class="object-contain __img radius-img" src="{{ $g_voucher['image']['url'] }}" alt="{{ $g_voucher['image']['alt'] ?? '' }}">
			</div>
			@endif

			<div class="__voucher order2 lg:py-10">
				<h4 data-gsap-element="header" class="">{{ $g_voucher['header'] }}</h4>

				<div data-gsap-element="txt" class="__txt mt-4">
					{!! $g_voucher['txt'] !!}
				</div>

				@if (!empty($g_voucher['hint']))
				<div data-gsap-element="box" class="__hint flex items-center radius bg-primary-lighter border border-dashed border-primary p-6 gap-4 mt-6">
					@if (!empty($g_voucher['image_hint']['url']))
					<img
						class="max-w-10 aspect-square"
						src="{{ $g_voucher['image_hint']['url'] }}"
						alt="{{ $g_voucher['image_hint']['alt'] ?? '' }}">
					@endif

					@if (!empty($g_voucher['header_hint']))
					<div class="">
						{{ $g_voucher['header_hint'] }}
					</div>
					@endif
				</div>
				@endif

				

			@if (!empty($g_voucher['product']->ID))
			@if ($g_voucher['button_type'] === 'product_link' && !empty($g_voucher['product_data']['permalink']))
			<x-button
				:href="$g_voucher['product_data']['permalink']"
				variant="primary"
				class="mt-4"
				 data-gsap-element="btn">
				{{ $g_voucher['btn'] ?: 'Zobacz produkt' }}
			</x-button>
			@elseif ($g_voucher['button_type'] === 'add_to_cart' && !empty($g_voucher['product_data']['add_to_cart_url']))
			<x-button
				:href="wc_get_cart_url() . '?add-to-cart=' . $g_voucher['product']->ID"
				variant="primary"
				class="mt-4 add_to_cart_button"
				 data-gsap-element="btn">
				{{ $g_voucher['btn'] ?: 'Dodaj do koszyka' }}
			</x-button>
			@endif
			@endif

			</div>

		</div>
	</div>

</section>