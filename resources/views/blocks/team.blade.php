@php
$sectionClass = '';
$sectionClass .= $flip ? ' order-flip' : '';
$sectionClass .= $nolist ? ' no-list' : '';
$sectionClass .= $wide ? ' wide' : '';
$sectionClass .= $nomt ? ' !mt-0' : '';
$sectionClass .= $gap ? ' wider-gap' : '';

if (!empty($background) && $background !== 'none') {
$sectionClass .= ' ' . $background;
}
@endphp

<section data-gsap-anim="section" @if(!empty($section_id)) id="{{ $section_id }}" @endif class="b-team relative overflow-visible -smt {{ $sectionClass }} {{ $section_class }}">

	<div class="__wrapper c-main grid grid-cols-1 md:grid-cols-2 gap-10">
		<h2 data-gsap-element="header" class="m-header">{{ strip_tags($g_team['header']) }}</h2>
		<div data-gsap-element="txt" class="">
			{!! $g_team['content'] !!}
		</div>
	</div>

	@if (!empty($team_posts))
	<div class="c-main pt-12">
		<div class="swiper team-swiper !overflow-visible">
			<div class="swiper-wrapper">
				@foreach ($team_posts as $post)
				@php
				setup_postdata($GLOBALS['post'] =& $post);

				$terms = get_the_terms(get_the_ID(), 'category') ?: [];
				$display_terms = array_values(array_filter($terms, function ($term) {
				return $term->slug !== 'specjalisci';
				}));
				@endphp

				<div class="swiper-slide h-auto">
					<a href="{{ get_permalink() }}" class="block h-full">
						<div data-gsap-element="card" class="__card relative bg-white radius p-6 h-full">
							@if (has_post_thumbnail())
							<div class="radius-img">
								<img class="h-[360px] w-full radius-img object-cover mb-6" src="{{ get_the_post_thumbnail_url(get_the_ID(), 'large') }}" alt="{{ get_the_title() }}" />
							</div>
							@endif

							<h6 class="text-h7 !text-body">{{ get_the_title() }}</h6>

							@if (!empty($display_terms))
							<p class="!text-body text-sm mt-2"> @foreach ($display_terms as $i => $term){{ $term->name }} @if ($i + 1 < count($display_terms)), @endif @endforeach
									</p>
									@endif
						</div>
					</a>
				</div>

				@endforeach
				@php(wp_reset_postdata())
			</div>

			<div data-gsap-element="arrows" class="absolute top-1/2 left-0 w-full -translate-y-1/2 z-10 flex justify-between items-center pointer-events-none">
				<div class="__prev rounded-full bg-secondary h-14 w-14 flex items-center justify-center pointer-events-auto -translate-x-1/2 cursor-pointer transition-all duration-400">
					<svg xmlns="http://www.w3.org/2000/svg" width="13" height="12" viewBox="0 0 13 12" fill="none">
						<path d="M0.270429 5.31498C0.270706 5.31469 0.270937 5.31435 0.27126 5.31406L5.08882 0.281803C5.44973 -0.0951806 6.03348 -0.0937777 6.39273 0.285093C6.75194 0.663916 6.75055 1.27664 6.38964 1.65367L3.15514 5.03226L12.078 5.03226C12.5872 5.03226 13 5.46552 13 6C13 6.53448 12.5872 6.96774 12.078 6.96774L3.15518 6.96774L6.3896 10.3463C6.75051 10.7234 6.75189 11.3361 6.39269 11.7149C6.03344 12.0938 5.44963 12.0951 5.08877 11.7182L0.271213 6.68594C0.270936 6.68565 0.270706 6.68531 0.270383 6.68502C-0.0907122 6.30673 -0.08956 5.69202 0.270429 5.31498Z" fill="#FFF" />
					</svg>
				</div>
				<div class="__next rounded-full bg-secondary h-14 w-14 flex items-center justify-center pointer-events-auto translate-x-1/2 cursor-pointer transition-all duration-300">
					<svg xmlns="http://www.w3.org/2000/svg" width="13" height="12" viewBox="0 0 13 12" fill="none">
						<path d="M12.7296 5.31498C12.7293 5.31469 12.7291 5.31435 12.7287 5.31406L7.91118 0.281803C7.55027 -0.0951806 6.96652 -0.0937777 6.60727 0.285093C6.24806 0.663916 6.24945 1.27664 6.61036 1.65367L9.84486 5.03226L0.921985 5.03226C0.412773 5.03226 0 5.46552 0 6C0 6.53448 0.412773 6.96774 0.921985 6.96774L9.84482 6.96774L6.6104 10.3463C6.24949 10.7234 6.24811 11.3361 6.60731 11.7149C6.96657 12.0938 7.55037 12.0951 7.91123 11.7182L12.7288 6.68594C12.7291 6.68565 12.7293 6.68531 12.7296 6.68502C13.0907 6.30673 13.0896 5.69202 12.7296 5.31498Z" fill="#FFF" />
					</svg>
				</div>
			</div>
		</div>
	</div>
	@endif


	@if (!empty($g_team['image']))
	<img data-gsap-element="bg" class="__bg absolute -bottom-10 -left-10 pointer-events-none" src="{{ $g_team['image']['url'] }}" />
	@endif
</section>