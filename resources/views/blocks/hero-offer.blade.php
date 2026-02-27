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

<!-- hero-offer -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	class="b-hero-offer bg-gradient relative overflow-hidden {{ $sectionClass }} {{ $section_class }}">

	<div class="__wrapper c-wide grid grid-cols-1 lg:grid-cols-[3fr_1fr] gap-8 items-end relative z-20 py-30">
		<div class="__content relative z-20 pt-20 pb-10 md:py-30">

			<h1 data-gsap-element="header" class="text-white bg-bg-brand">
				{{ $g_hero_offer['header'] }}
			</h1>
		<div data-gsap-element="txt" class="text-white mt-2 w-full md:w-2/3">
				{!! $g_hero_offer['txt'] !!}
			</div> 
			@if (!empty($g_hero_offer['button1']))
			<div class="inline-buttons m-btn">
				<a data-gsap-element="button" class="second-btn left-btn"
					href="{{ $g_hero_offer['button1']['url'] }}"
					target="{{ $g_hero_offer['button1']['target'] }}">
					{{ $g_hero_offer['button1']['title'] }}
				</a>
				@if (!empty($g_hero_offer['button2']))
				<a data-gsap-element="button" class="white-btn"
					href="{{ $g_hero_offer['button2']['url'] }}"
					target="{{ $g_hero_offer['button2']['target'] }}">
					{{ $g_hero_offer['button2']['title'] }}
				</a>
				@endif
			</div>
			@endif
		</div>

   @if ($g_hero_offer['image'])
         <div class="__img absolute top-0 bottom-0 right-0 w-1/2">
            <img data-gsap-element="image" class="absolute left-0 bottom-12" src="/wp-content/uploads/2026/01/small-shape.svg" />
            <div data-gsap-element="image" class="__mask" style="background-image: url('{{ $g_hero_offer['image']['url'] }}')"></div>
        </div>
        @endif

	</div>

</section>