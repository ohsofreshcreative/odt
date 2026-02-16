@php
// Sprawdzamy, czy kategoria została wybrana w bloku
$category_id = !empty($g_mats['category']) ? $g_mats['category'] : null;
$posts_query = null;

if ($category_id) {
// Tworzymy zapytanie o 3 wpisy z tej kategorii
$args = [
'post_type' => 'post',
'posts_per_page' => 3,
'cat' => $category_id,
];
$posts_query = new WP_Query($args);
}
@endphp

<!--- mats --->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-mats relative c-main -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper relative bg-white radius p-8">
		<div class="flex justify-between items-center">
			@if(!empty($g_mats['header']))
			<h4 data-gsap-element="header" class="">{{ $g_mats['header'] }}</h4>
			@endif

			@if ($category_id)
			@php
			$button_title = !empty($g_mats['button']['title']) ? $g_mats['button']['title'] : 'Zobacz wszystkie';
			@endphp
			<div data-gsap-element="btn">
				<a href="{{ get_category_link($category_id) }}" class="main-btn">{{ $button_title }}</a>
			</div>
			@endif
		</div>

		@if ($posts_query && $posts_query->have_posts())
		<div class="posts-grid mt-8">
			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
				@while ($posts_query->have_posts()) @php $posts_query->the_post() @endphp
				<div data-gsap-element="card" class="">
					<a href="{{ get_permalink() }}">
						@if (has_post_thumbnail())
						<div class="aspect-w-16 aspect-h-9">
							{!! get_the_post_thumbnail(get_the_ID(), 'medium_large', ['class' => 'w-full h-full object-cover radius-img aspect-square group-hover:scale-105 transition-transform duration-300']) !!}
						</div>
						@else

						<div class="aspect-w-16 aspect-h-9 bg-light flex items-center justify-center">
							<span class="text-gray-400">Brak obrazka</span>
						</div>
						@endif
					</a>
					<div class="mt-6">
						<h6 class="">
							<a href="{{ get_permalink() }}" class="hover:text-primary transition-colors">{{ get_the_title() }}</a>
						</h6>
						<a href="{{ get_permalink() }}" class="underline-btn mt-4">
							Zobacz
						</a>
					</div>
				</div>
				@endwhile
			</div>
			@php wp_reset_postdata() @endphp
		</div>
		@endif
	</div>
</section>