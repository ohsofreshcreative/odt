@php
$sectionClass = '';
$sectionClass .= !empty($flip) ? ' order-flip' : '';
@endphp

<!--- resources --->

<section data-gsap-anim="section" class="resources relative -mt-20 z-10 {{ $sectionClass }} {{ $section_class ?? '' }}">
	<div class="__wrapper c-main grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-8">

		{{-- Kolumna 1 --}}
		@if (!empty($col_1_data['category']))
		@php
		$args1 = [
		'post_type' => 'post',
		'posts_per_page' => 3,
		'cat' => $col_1_data['category'],
		];
		$query1 = new WP_Query($args1);
		$category_link_1 = get_category_link($col_1_data['category']);
		@endphp
		<div class="__first bg-white radius p-8">
			@if (!empty($col_1_data['title']))
			<h3 class="mt-5 text-primary">{{ $col_1_data['title'] }}</h3>
			@endif

			@if ($query1->have_posts())
			<ul class="space-y-6 mt-6">
				@while ($query1->have_posts()) @php $query1->the_post() @endphp
				<li>
					<a href="{{ get_permalink() }}" class="flex items-center gap-4 rounded-lg hover:bg-light transition-colors group">
						@if(has_post_thumbnail())
						<div class="w-16 h-16 rounded-md overflow-hidden flex-shrink-0">
							{!! get_the_post_thumbnail(get_the_ID(), 'thumbnail', ['class' => 'w-full h-full object-cover']) !!}
						</div>
						@endif
						<span class="group-hover:text-primary transition-colors">{{ get_the_title() }}</span>

						{{-- ZMIANA TUTAJ --}}
						<div class="ml-auto w-10 h-10 flex items-center justify-center rounded-full bg-secondary group-hover:bg-secondary-hover transition-colors">
							<svg class="text-primary group-hover:text-white transition-colors" xmlns="http://www.w3.org/2000/svg" width="13" height="12" viewBox="0 0 13 12" fill="#FFF">
								<path d="M12.7296 5.31498C12.7293 5.31469 12.7291 5.31435 12.7287 5.31406L7.91118 0.281803C7.55027 -0.0951806 6.96652 -0.0937777 6.60727 0.285093C6.24806 0.663916 6.24945 1.27664 6.61036 1.65367L9.84486 5.03226L0.921985 5.03226C0.412773 5.03226 0 5.46552 0 6C0 6.53448 0.412773 6.96774 0.921985 6.96774L9.84482 6.96774L6.6104 10.3463C6.24949 10.7234 6.24811 11.3361 6.60731 11.7149C6.96657 12.0938 7.55037 12.0951 7.91123 11.7182L12.7288 6.68594C12.7291 6.68565 12.7293 6.68531 12.7296 6.68502C13.0907 6.30673 13.0896 5.69202 12.7296 5.31498Z" />
							</svg>
						</div>
					</a>
				</li>
				@endwhile
			</ul>
			@php wp_reset_postdata() @endphp
			@endif

			@if ($category_link_1)
			<a href="{{ $category_link_1 }}" class="stroke-btn mt-8">Zobacz wszystkie</a>
			@endif
		</div>
		@endif

		{{-- Kolumna 2 --}}
		@if (!empty($col_2_data['category']))
		@php
		$args2 = [
		'post_type' => 'post',
		'posts_per_page' => 3,
		'cat' => $col_2_data['category'],
		];
		$query2 = new WP_Query($args2);
		$category_link_2 = get_category_link($col_2_data['category']);
		@endphp
		<div class="__second bg-white radius p-8">
			@if (!empty($col_2_data['title']))
			<h3 class="mt-5 text-primary">{{ $col_2_data['title'] }}</h3>
			@endif

			@if ($query2->have_posts())
			<ul class="space-y-6 mt-6">
				@while ($query2->have_posts()) @php $query2->the_post() @endphp
				<li>
					<a href="{{ get_permalink() }}" class="flex items-center gap-4 rounded-lg hover:bg-light transition-colors group">
						@if(has_post_thumbnail())
						<div class="w-16 h-16 rounded-md overflow-hidden flex-shrink-0">
							{!! get_the_post_thumbnail(get_the_ID(), 'thumbnail', ['class' => 'w-full h-full object-cover']) !!}
						</div>
						@endif
						<span class="group-hover:text-primary transition-colors">{{ get_the_title() }}</span>

						{{-- ZMIANA TUTAJ --}}
						<div class="ml-auto w-10 h-10 flex items-center justify-center rounded-full bg-secondary group-hover:bg-secondary-hover transition-colors">
							<svg class="text-primary group-hover:text-white transition-colors" xmlns="http://www.w3.org/2000/svg" width="13" height="12" viewBox="0 0 13 12" fill="#FFF">
								<path d="M12.7296 5.31498C12.7293 5.31469 12.7291 5.31435 12.7287 5.31406L7.91118 0.281803C7.55027 -0.0951806 6.96652 -0.0937777 6.60727 0.285093C6.24806 0.663916 6.24945 1.27664 6.61036 1.65367L9.84486 5.03226L0.921985 5.03226C0.412773 5.03226 0 5.46552 0 6C0 6.53448 0.412773 6.96774 0.921985 6.96774L9.84482 6.96774L6.6104 10.3463C6.24949 10.7234 6.24811 11.3361 6.60731 11.7149C6.96657 12.0938 7.55037 12.0951 7.91123 11.7182L12.7288 6.68594C12.7291 6.68565 12.7293 6.68531 12.7296 6.68502C13.0907 6.30673 13.0896 5.69202 12.7296 5.31498Z" />
							</svg>
						</div>
					</a>
				</li>
				@endwhile
			</ul>
			@php wp_reset_postdata() @endphp
			@endif

			@if ($category_link_2)
			<a href="{{ $category_link_2 }}" class="stroke-btn mt-8">Zobacz wszystkie</a>
			@endif
		</div>
		@endif

	</div>
</section>