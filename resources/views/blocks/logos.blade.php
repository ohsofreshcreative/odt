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
		<div class="mt-6 grid grid-cols-2 md:grid-cols-4 items-center gap-6">
			
			@foreach ($g_logos['gallery'] as $image)
			<div class="bg-white flex items-center justify-center p-4 rounded-lg">
			<img src="{{ $image['url'] }}" alt="{{ $image['alt'] }}" class="max-h-16 w-auto">
			</div>
			@endforeach
		</div>
		@endif
	</div>

</section>