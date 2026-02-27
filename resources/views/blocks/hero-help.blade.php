@php
$sectionClass = '';
$sectionClass .= $flip ? ' order-flip' : '';
$sectionClass .= $wide ? ' wide' : '';
$sectionClass .= $nomt ? ' !mt-0' : '';
$sectionClass .= $gap ? ' wider-gap' : '';
$sectionClass .= $lightbg ? ' section-light' : '';
$sectionClass .= $graybg ? ' section-gray' : '';
$sectionClass .= $whitebg ? ' section-white' : '';
$sectionClass .= $brandbg ? ' section-brand' : '';
@endphp

<!-- hero-help -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	class="b-hero-help bg-gradient relative {{ $sectionClass }} {{ $section_class }}">

	<div class="__wrapper c-wide grid grid-cols-1 lg:grid-cols-[1.5fr_1fr] gap-8 items-center relative z-20 py-30">
		<div class="__content relative z-20 pt-20 pb-10 md:py-30">

			<h1 data-gsap-element="header" class="text-white bg-bg-brand">
				{{ $g_hero_help['header'] }}
			</h1>
			<div data-gsap-element="txt" class="text-white mt-4 w-full md:w-2/3">
				{!! $g_hero_help['txt'] !!}
			</div>
			@if (!empty($g_hero_help['button1']))
			<div class="inline-buttons m-btn">
				<a data-gsap-element="button" class="second-btn left-btn"
					href="{{ $g_hero_help['button1']['url'] }}"
					target="{{ $g_hero_help['button1']['target'] }}">
					{{ $g_hero_help['button1']['title'] }}
				</a>
				@if (!empty($g_hero_help['button2']))
				<a data-gsap-element="button" class="white-btn"
					href="{{ $g_hero_help['button2']['url'] }}"
					target="{{ $g_hero_help['button2']['target'] }}">
					{{ $g_hero_help['button2']['title'] }}
				</a>
				@endif
			</div>
			@endif
		</div>

		@if ($g_hero_help['image'])
		<div class="">
			<img data-gsap-element="image" class="max-w-[440px]" src="{{ $g_hero_help['image']['url'] }}" />
		</div>
		@endif
	</div>

	<img data-gsap-element="image" class="absolute top-0 -right-20 h-full" src="/wp-content/uploads/2026/01/help-hero-bg.svg" />
</section>