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

<!--- offer --->

<section data-gsap-anim="section" @if(!empty($section_id)) id="{{ $section_id }}" @endif class="b-offer relative -smt {{ $sectionClass }} {{ $section_class }}">

	<div class="__wrapper c-main relative z-10">
		<div class="grid grid-cols-1 md:grid-cols-2 gap-12">
			<h3 data-gsap-element="header" class="relative z-10">
				{{ $g_offer['header'] }}
			</h3>
			<div data-gsap-element="txt" class="__txt mt-2">
				{!! $g_offer['txt'] !!}
			</div>
		</div>

		<div class="__list grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 section-gap mt-12">
			@if($offer)
			@foreach ($offer as $sector)
			<a href="{{ get_permalink($sector->ID) }}" class="">
				<div data-gsap-element="item" class="__card bg-white radius p-6">
					@if (has_post_thumbnail($sector->ID))
					<img class="w-20 aspect-square radius" src="{{ get_the_post_thumbnail_url($sector->ID, 'large') }}" alt="{{ $sector->post_title }}">
					@endif
					<h6 class="!text-primary text-h7 mt-4">{{ $sector->post_title }}</h6>
					<p class="!text-body text-sm mt-2">{{ $sector->post_excerpt }}</p>

					<p class="underline-btn mt-4">
						Dowiedz się więcej
					</p>
				</div>
			</a>
			@endforeach
			@endif
		</div>

		@if(!empty($show_all_button))
		<div data-gsap-element="btn" class="text-center mt-12">
			<a href="{{ get_post_type_archive_link('offer') ?: '#' }}" class="second-btn mx-auto">
				Zobacz całą ofertę
			</a>
		</div>
		@endif
	</div>

	@if (!empty($g_offer['image']))
	<img data-gsap-element="bg" class="__bg absolute left-1/2 -translate-x-1/2 top-1/2 -translate-y-1/2 opacity-20 pointer-events-none" src="{{ $g_offer['image']['url'] }}" />
	@endif

	@if (!empty($g_offer['image2']))
	<img data-gsap-element="bg" class="__bg absolute -right-30 -bottom-40 pointer-events-none" src="{{ $g_offer['image2']['url'] }}" />
	@endif
</section>