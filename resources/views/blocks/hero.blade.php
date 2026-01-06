@php
$sectionClass = '';
$sectionClass .= $nomt ? ' !mt-0' : '';
@endphp

<!-- hero --->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	class="b-hero bg-gradient relative {{ $sectionClass }} {{ $section_class }}">

	<div class="__wrapper c-wide grid grid-cols-1 lg:grid-cols-[2fr_1fr] gap-8 items-end relative z-20 py-30">
		<div class="__content relative z-20 pt-20 pb-10 md:py-30">

			<h1 data-gsap-element="header" class="text-white bg-bg-brand">
				{{ $g_hero['title'] }}
			</h1>
			<div data-gsap-element="txt" class="text-xl md:text-2xl text-white mt-2 w-full md:w-2/3">
				{!! $g_hero['txt'] !!}
			</div>
			@if (!empty($g_hero['button1']))
			<div class="inline-buttons m-btn">
				<a data-gsap-element="button" class="second-btn left-btn"
					href="{{ $g_hero['button1']['url'] }}"
					target="{{ $g_hero['button1']['target'] }}">
					{{ $g_hero['button1']['title'] }}
				</a>
				@if (!empty($g_hero['button2']))
				<a data-gsap-element="button" class="white-btn"
					href="{{ $g_hero['button2']['url'] }}"
					target="{{ $g_hero['button2']['target'] }}">
					{{ $g_hero['button2']['title'] }}
				</a>
				@endif
			</div>
			@endif
		</div>

		@if ($g_hero['image'])
		<div class="__img absolute -right-38 h-full">
			<img data-gsap-element="image" class="absolute left-1/3 bottom-12 z-10" src="/wp-content/uploads/2026/01/shape.svg" />
			<img data-gsap-element="image" class="__mask object-cover" src="{{ $g_hero['image']['url'] }}" alt="{{ $g_hero['image']['alt'] ?? '' }}">
		</div>
		@endif
	</div>

	<img data-gsap-element="image" class="absolute -left-40 -bottom-20" src="/wp-content/uploads/2026/01/plant.svg" />

</section>