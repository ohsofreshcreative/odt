@php
defined('ABSPATH') || exit;
global $product;
@endphp

<li class="relative flex flex-col lg:flex-row lg:items-center border-b border-dashed border-gray-300 gap-6 pb-4">
	@if($product && $product->is_on_sale())
	<span class="onsale">Promocja!</span>
	@endif

	<figure class="woocommerce-product-figure relative !mb-0 mr-4 flex-shrink-0">
		<a href="{{ get_permalink() }}">
			<img src="{{ get_the_post_thumbnail_url($product->get_id(), '') }}"
				alt="{{ get_the_title() }}" class="img-xs max-h-52 !max-w-30 !object-cover aspect-square" />
		</a>
	</figure>

	<div class="flex flex-col lg:flex-row flex-grow lg:items-center w-full">
		<div class="flex-grow mb-4 lg:mb-0 text-center lg:text-left">
			<h5 class="woocommerce-loop-product__title text-h7">
				<a class="block" href="{{ get_permalink() }}">{!! get_the_title() !!}</a>
			</h5>
			<p>Kod produktu: {{ $product->get_sku() }}</p>
		</div>

		<div class="w-full lg:w-32 text-center font-bold lg:flex-shrink-0 mb-4 lg:mb-0">
			<span class="lg:hidden font-normal">Cena: </span>{!! $product->get_price_html() !!}
		</div>

		<div class="w-full lg:w-32 text-center lg:flex-shrink-0 mb-4 lg:mb-0">
		
			<span class="lg:hidden font-normal">Dostępność: </span>
			@if ($product->is_type('variable'))
			@if ($product->is_in_stock()) Dostępny @else Brak @endif
			@else
			@if ($product->get_stock_status() === 'instock') Na stanie
			@elseif ($product->get_stock_status() === 'onbackorder') Na zamówienie
			@else Brak @endif
			@endif
		</div>

		<div class="w-full lg:w-48 flex items-center justify-center lg:justify-end lg:flex-shrink-0 gap-2">
			@if ($product->is_type('simple') && $product->is_purchasable() && $product->is_in_stock())
			<div class="quantity-wrapper flex items-center justify-center bg-white border border-gray-300 rounded-xl h-10 w-28">
				<button type="button" class="quantity-button quantity-minus h-full hover:bg-gray-100 rounded-l-xl transition-all px-3">-</button>
				@php
				woocommerce_quantity_input([
				'min_value' => $product->get_min_purchase_quantity(),
				'max_value' => $product->get_max_purchase_quantity(),
				'input_value' => $product->get_min_purchase_quantity(),
				], $product, true);
				@endphp
				<button type="button" class="quantity-button quantity-plus h-full hover:bg-gray-100 rounded-r-xl transition-all px-3">+</button>
			</div>
			@endif

			@php do_action('woocommerce_after_shop_loop_item'); @endphp
		</div>
	</div>
</li>