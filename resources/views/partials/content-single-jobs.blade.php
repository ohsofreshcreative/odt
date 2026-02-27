@php
$categories = get_the_category();
$category = !empty($categories) ? $categories[0] : null;
@endphp

<section data-gsap-anim="section" class="hero-blog bg-gradient relative overflow-visible">
	<div class="__wrapper c-main relative z-10 -spt">
		<div class="__content w-full pb-30">
			<div data-gsap-element="bread" class="__breadcrumb">
				@if (function_exists('woocommerce_breadcrumb'))
				{!! woocommerce_breadcrumb() !!}
				@endif
			</div>

			<div class="__top mt-20">
				<p class="text-secondary font-header">Praca</p>
				<h1 data-gsap-element="header" class="text-h2 text-white mt-6">{{ get_the_title() }}</h1>
				@if(has_excerpt())
				<div data-gsap-element="content" class="text-white mt-4">
					{!! get_the_excerpt() !!}
				</div>
				@endif
			</div>
		</div>
	</div>
	<img src="/wp-content/uploads/2026/01/blog-leaf.svg" alt="" class="absolute -top-20 -right-20 pointer-events-none">
</section>

<div id="tresc" class="__entry -smt">
	<div class="w-full">
		{!! the_content() !!}
	</div>
</div>

@php
$current_id = get_the_ID();
$categories = wp_get_post_categories($current_id);
$related_args = [
'category__in' => $categories,
'post__not_in' => [$current_id],
'posts_per_page' => 3,
'ignore_sticky_posts' => 1,
];
$related_query = new WP_Query($related_args);
@endphp