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

<!--- jobs --->

<section data-gsap-anim="section" @if(!empty($section_id)) id="{{ $section_id }}" @endif class="b-jobs relative -spt {{ $sectionClass }} {{ $section_class }}">
	<div class="__wrapper c-main relative">
		@if (!empty($g_jobs['title']))
		<h2 data-gsap-element="header" class="">{{ $g_jobs['title'] }}</h2>
		@endif

		<div class="__col grid gap-10 mt-10">
			@if($jobs)
			@foreach ($jobs as $sector)
			<div class="__card bg-white">
				@if (has_post_thumbnail($sector->ID))
				<a href="{{ get_permalink($sector->ID) }}">
					<img src="{{ get_the_post_thumbnail_url($sector->ID, 'large') }}" alt="{{ $sector->post_title }}" class="w-full img-s object-cover rounded-t-2xl">
				</a>
				@endif
				<div class="__content relative bg-white border border-secondary radius px-10 py-6 flex items-center justify-between gap-6">
					<h6 class="">
						<a href="{{ get_permalink($sector->ID) }}">{{ $sector->post_title }}</a>
					</h6>
					@php
					$location = get_field('job_location', $sector->ID);
					$time_dimension = get_field('job_time_dimension', $sector->ID);
					@endphp
					<div class="flex gap-10">
						@if ($location)
						<div class="flex items-center gap-2 text-sm">
							  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
							<span>{{ $location }}</span>
						</div>
						@endif
						@if ($time_dimension)
						<div class="flex items-center gap-2 text-sm">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<circle cx="12" cy="12" r="10"></circle>
								<polyline points="12 6 12 12 16 14"></polyline>
							</svg>
							<span>{{ $time_dimension }}</span>
						</div>
						@endif
					</div>
					<a href="{{ get_permalink($sector->ID) }}" class="second-btn">
						Aplikuj
					</a>
				</div>
			</div>
			@endforeach
			@endif
		</div>
	</div>
</section>