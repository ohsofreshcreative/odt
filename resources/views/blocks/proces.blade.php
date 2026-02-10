<!--- proces --->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-proces relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main">
		<div class="">
			<div class="__top grid grid-cols-1 lg:grid-cols-2 items-center gap-10 relative z-10">
				@if (!empty($g_proces['header']))
				<h3 data-gsap-element="header" class=" text-secondary-dark m-header">{{ strip_tags($g_proces['header']) }}</h3>
				@endif
				<div data-gsap-element="txt" class="w-full md:w-2/3 ml-auto">{{ strip_tags($g_proces['txt']) }}</div>
			</div>

			<img class="absolute left-1/2 -translate-x-1/2 top-10" src="{{ $g_proces['image']['url'] }}" alt="{{ $g_proces['image']['alt'] ?? '' }}" />
		</div>

		@if (!empty($r_proces))
        @php
            $repeater_count = count($r_proces);
            $grid_class = 'lg:grid-cols-4'; // Domyślna klasa
            if ($repeater_count === 3) {
                $grid_class = 'lg:grid-cols-3';
            }
        @endphp
		<div class="__repeater gap-8 grid grid-cols-1 md:grid-cols-2 {{ $grid_class }} mt-16">

			@foreach ($r_proces as $item)
			<div data-gsap-element="stagger" class="flex flex-col radius bg-secondary-lighter overflow-hidden pt-6 pb-40 px-6">
				<div class="relative z-20">
					<div class="text-h2 font-header text-secondary-dark">{{ $loop->iteration }}</div>

					<h6 class=" text-secondary-dark mt-4">{{ $item['title'] }}</h6>
					<p class="mt-2">{{ $item['txt'] }}</p>
				</div>

				<img class="absolute -bottom-6 -right-6 z-10" src="{{ $item['image']['url'] }}" alt="{{ $item['image']['alt'] ?? '' }}" />
			</div>
			@endforeach
		</div>
		<div class="__line absolute bg-primary z-0 origin-left scale-x-0"></div>
		@endif
	</div>

</section>