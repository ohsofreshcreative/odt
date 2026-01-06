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

<!--- numbers --->

<section data-gsap-anim="section" @if(!empty($section_id)) id="{{ $section_id }}" @endif class="b-numbers -smt {{ $sectionClass }} {{ $section_class }}">

	<div class="__wrapper c-main">
		<div class="">
			<div class="grid grid-cols-1 md:grid-cols-[1fr_2fr_1fr] gap-8">
				@if (!empty($g_numbers['header']))
				<h4 data-gsap-element="header" class="">{{ strip_tags($g_numbers['header']) }}</h4>
				@endif
				<div data-gsap-element="txt" class="mt-2">
					{!! $g_numbers['txt'] !!}
				</div>
				@if (!empty($g_numbers['image']))
				<img data-gsap-element="txt" class="" src="{{ $g_numbers['image']['url'] }}" alt="{{ $g_numbers['image']['alt'] ?? '' }}">
				@endif
			</div>

			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 border-top-p mt-14">
				@foreach ($g_numbers['r_numbers'] as $item)
				<div data-gsap-element="card" class="__card relative bg-primary-lighter radius p-6">
					<p class="text-h2">{{ $item['title'] }}</p>
					<p class="">{{ $item['txt'] }}</p>
				</div>
				@endforeach
			</div>

		</div>
	</div>

</section>