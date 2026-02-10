@php
$sectionClass = '';
$sectionClass .= $flip ? ' order-flip' : '';
$sectionClass .= $wide ? ' wide' : '';
$sectionClass .= $nomt ? ' !mt-0' : '';
$sectionClass .= $gap ? ' wider-gap' : '';

if (!empty($background) && $background !== 'none') {
$sectionClass .= ' ' . $background;
}
@endphp

<!--- job -->

<section data-gsap-anim="section" @if(!empty($section_id)) id="{{ $section_id }}" @endif class="b-job relative {{ $sectionClass }} {{ $section_class }}">

	<div class="__wrapper c-main relative grid grid-cols-1 md:grid-cols-[3fr_1fr] gap-8 md:gap-16">
		<div class="__content">
			<div data-gsap-element="txt" class="__txt mt-2">
				{!! $g_job['txt'] !!}
			</div>

			@if (!empty($g_job['button']))
			<a data-gsap-element="btn" class="main-btn m-btn align-self-bottom" href="{{ $g_job['button']['url'] }}">{{ $g_job['button']['title'] }}</a>
			@endif

			<div data-gsap-element="form" id="aplikuj" class="__form bg-white radius p-8 md:p-10 lg:p-14 mt-10">
				<h5 class="relative text-primary mb-6 z-10">{{ $g_job_2['title'] }}</h5>
				<div class="relative z-10">{!! do_shortcode($g_job_2['shortcode']) !!}</div>
			</div>
		</div>

		<div class="w-full relative md:sticky top-20 h-max">
			@php
			// Używamy ID przekazanego z kontrolera bloku
			$location = get_field('job_location', $current_post_id);
			$time_dimension = get_field('job_time_dimension', $current_post_id);
			$job_title = get_the_title($current_post_id);
			@endphp

			@if ($location || $time_dimension)
			<div class="job-details bg-white p-6 rounded-xl">
				<p class="text-action">Aplikuj na stanowisko</p>
				<h3 class="text-primary text-h5 mb-3">{{ $job_title }}</h3>
				<ul class="flex flex-col md:flex-row gap-4">
					@if ($location)
					<li class="flex items-center gap-3">
						<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#DE855D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
							<circle cx="12" cy="10" r="3"></circle>
						</svg>
						<span>{{ $location }}</span>
					</li>
					@endif
					@if ($time_dimension)
					<li class="flex items-center gap-3">
						<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#DE855D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<circle cx="12" cy="12" r="10"></circle>
							<polyline points="12 6 12 12 16 14"></polyline>
						</svg>
						<span>{{ $time_dimension }}</span>
					</li>
					@endif
				</ul>
				<a href="#aplikuj" class="main-btn mt-6">
					Aplikuj
				</a>
			</div>
			@endif
		</div>
</section>