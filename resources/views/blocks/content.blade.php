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

<!--- content -->

<section data-gsap-anim="section" @if(!empty($section_id)) id="{{ $section_id }}" @endif class="b-content relative -smt {{ $sectionClass }} {{ $section_class }}">

	<div class="__wrapper c-main relative">
		<div class="__col grid grid-cols-1 lg:grid-cols-2 items-center gap-8 lg:gap-20">
			@if (!empty($g_content['image']))
			<div data-gsap-element="img" class="__img h-full order1">
				<img class="object-cover w-full h-full aspect-square __img radius-img" src="{{ $g_content['image']['url'] }}" alt="{{ $g_content['image']['alt'] ?? '' }}">
			</div>
			@endif

			<div class="__content order2 lg:py-10">
				<h4 data-gsap-element="header" class="">{{ $g_content['header'] }}</h4>

				<div data-gsap-element="txt" class="__txt mt-2">
					{!! $g_content['txt'] !!}
				</div>

				@if (!empty($g_content['hint']))
				<div data-gsap-element="box" class="__hint flex items-center radius bg-primary-lighter border border-dashed border-primary p-6 gap-4 mt-6">
					@if (!empty($g_content['image_hint']['url']))
					<img
						class=""
						src="{{ $g_content['image_hint']['url'] }}"
						alt="{{ $g_content['image_hint']['alt'] ?? '' }}">
					@endif

					@if (!empty($g_content['header_hint']))
					<div class="">
						{{ $g_content['header_hint'] }}
					</div>
					@endif
				</div>
				@endif

				@if (!empty($g_content['button']))
				<a data-gsap-element="btn" class="main-btn m-btn align-self-bottom" href="{{ $g_content['button']['url'] }}">{{ $g_content['button']['title'] }}</a>
				@endif

			</div>

		</div>
	</div>

</section>