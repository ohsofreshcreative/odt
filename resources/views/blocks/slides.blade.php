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

<!--- slides -->

<section data-gsap-anim="section" @if(!empty($section_id)) id="{{ $section_id }}" @endif class="b-slides relative -smt overflow-hidden {{ $sectionClass }} {{ $section_class }}">

	<div class="__wrapper c-main block">
		<h2 class="w-3/4 mb-10">{{ $g_slides['title']}}</h2>
	</div>

	<div class="swiper slides-swiper c-main !overflow-hidden">

		<div class="swiper-wrapper">

			@foreach($slides as $card)
			<div class="swiper-slide">
				<div class="__card grid grid-cols-1 md:grid-cols-[1.5fr_1fr] items-center gap-6">
					<div class="__content">
						@if(!empty($card['txt']))
						<h5 class="font-normal">{{ $card['txt'] }}</h5>
						@endif

						<div class="__person flex items-center gap-4 mt-4">
							@if(!empty($card['photo']))
							<div class="__img w-22 h-22 rounded-full overflow-hidden">
								{!! wp_get_attachment_image($card['photo']['ID'], 'medium', false, ['class' => 'img-fluid']) !!}
							</div>
							@endif
							@if(!empty($card['person']))
							<h6 class="">{{ $card['person'] }}</h6>
							@endif
						</div>
					</div>

					@if(!empty($card['image']))
					<div class="__img relative w-10/12 ml-auto">
						{!! wp_get_attachment_image($card['image']['ID'], 'full', false, ['class' => 'img-xl object-cover radius-img']) !!}
						@if(!empty($card['video']))
						<button class="absolute inset-0 w-full h-full flex items-center justify-center" data-video-src="{{ $card['video'] }}">
							<span class="w-20 h-20 bg-secondary/60 rounded-full flex items-center justify-center text-white">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-play">
									<polygon points="5 3 19 12 5 21 5 3"></polygon>
								</svg>
							</span>
						</button>
						@endif
					</div>
					@endif
				</div>
			</div>
			@endforeach
		</div>
	</div>

	<div class="__navigation c-main flex items-center justify-center gap-6 !mt-8">
		<button class="swiper-button-prev-custom w-14 h-14 flex items-center justify-center rounded-full cursor-pointer bg-secondary hover:bg-secondary-hover transition-colors" aria-label="Previous slide">
			<svg xmlns="http://www.w3.org/2000/svg" width="13" height="12" viewBox="0 0 13 12" fill="none">
				<path d="M0.270429 5.31498C0.270705 5.31469 0.270937 5.31435 0.271259 5.31406L5.08882 0.281803C5.44973 -0.0951806 6.03348 -0.0937777 6.39273 0.285093C6.75194 0.663916 6.75055 1.27664 6.38964 1.65367L3.15514 5.03226L12.078 5.03226C12.5872 5.03226 13 5.46552 13 6C13 6.53448 12.5872 6.96774 12.078 6.96774L3.15518 6.96774L6.3896 10.3463C6.75051 10.7234 6.75189 11.3361 6.39269 11.7149C6.03343 12.0938 5.44963 12.0951 5.08877 11.7182L0.271213 6.68594C0.270936 6.68565 0.270706 6.68531 0.270383 6.68502C-0.0907125 6.30673 -0.0895596 5.69202 0.270429 5.31498Z" fill="white" />
			</svg>
		</button>

		<div class="swiper-progress flex-1 max-w-md !h-[1px] bg-white rounded-full !overflow-visible">
			<div class="swiper-progress-bar relative h-[3px] -top-[1px] bg-secondary transition-all duration-300 ease-out" style="width: 0%"></div>
		</div>

		<button class="swiper-button-next-custom w-14 h-14 flex items-center justify-center rounded-full cursor-pointer bg-secondary hover:bg-secondary-hover transition-colors" aria-label="Next slide">
			<svg xmlns="http://www.w3.org/2000/svg" width="13" height="12" viewBox="0 0 13 12" fill="none">
				<path d="M12.7296 5.31498C12.7293 5.31469 12.7291 5.31435 12.7287 5.31406L7.91118 0.281803C7.55027 -0.0951806 6.96652 -0.0937777 6.60727 0.285093C6.24806 0.663916 6.24945 1.27664 6.61036 1.65367L9.84486 5.03226L0.921985 5.03226C0.412773 5.03226 0 5.46552 0 6C0 6.53448 0.412773 6.96774 0.921985 6.96774L9.84482 6.96774L6.6104 10.3463C6.24949 10.7234 6.24811 11.3361 6.60731 11.7149C6.96657 12.0938 7.55037 12.0951 7.91123 11.7182L12.7288 6.68594C12.7291 6.68565 12.7293 6.68531 12.7296 6.68502C13.0907 6.30673 13.0896 5.69202 12.7296 5.31498Z" fill="white" />
			</svg>
		</button>
	</div>

</section>