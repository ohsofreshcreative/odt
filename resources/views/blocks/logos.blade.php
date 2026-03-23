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

<!--- logos -->

<section data-gsap-anim="section" @if(!empty($section_id)) id="{{ $section_id }}" @endif class="b-logos relative -smt {{ $sectionClass }} {{ $section_class }}">

	<div class="__wrapper c-main relative">
		<h4 data-gsap-element="header" class="w-full md:w-1/2">{{ $g_logos['title'] }}</h4>

		@if (!empty($g_logos['gallery']))
		<div class="mt-20 flex flex-wrap items-center justify-center gap-8">
			
			@foreach ($g_logos['gallery'] as $image)
			<img src="{{ $image['url'] }}" alt="{{ $image['alt'] }}" class="h-12 w-auto">
			@endforeach
		</div>
		@endif
	</div>

</section>