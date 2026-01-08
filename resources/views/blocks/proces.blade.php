<!--- proces --->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([
  'b-proces relative -smt',
  $sectionClass => filled($sectionClass),
  $section_class => filled($section_class),
  $background => filled($background) && $background !== 'none',
])>
	
	<div class="__wrapper c-main">
		<div class="grid grid-cols-1 lg:grid-cols-2 items-center gap-10">
			<div class="__content">
				@if (!empty($g_proces['header']))
				<h2 data-gsap-element="header" class="m-header">{{ strip_tags($g_proces['header']) }}</h2>
				@endif
			</div>
			<div data-gsap-element="txt" class="">{{ strip_tags($g_proces['txt']) }}</div>
		</div>

		@if (!empty($r_proces))
			<div class="__repeater gap-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 mt-16">

			@foreach ($r_proces as $item)
			<div data-gsap-element="stagger" class="flex flex-col bg-white rounded-3xl border-s-light px-6 py-10 md:px-8 md:py-20">
					<img class="" src="{{ $item['image']['url'] }}" alt="{{ $item['image']['alt'] ?? '' }}" />
					<h5 class="mt-4">{{ $item['title'] }}</h5>
					<p class="mt-2">{{ $item['txt'] }}</p>
				</div>
			@endforeach
		</div>
		<div class="__line absolute bg-primary z-0 origin-left scale-x-0"></div>
		@endif
	</div>

</section>