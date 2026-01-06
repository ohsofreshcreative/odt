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

<!--- cta-bg -->

<section data-gsap-anim="section" @if(!empty($section_id)) id="{{ $section_id }}" @endif class="b-cta-bg c-main relative -smt {{ $sectionClass }} {{ $section_class }}">

	<div class="__wrapper py-12 radius" style="background-image:linear-gradient(rgba(19,42,35,0.7), rgba(13, 63, 47,0.7)), url('{{ $cta_bg['image']['url'] }}'); background-size:cover; background-position:center;">

		<div class="__inside grid grid-cols-1 md:grid-cols-[2fr_1fr] items-center gap-6 px-12">
			<div class="__content">
				@if ($cta_bg['header'])
				<h5 data-gsap-element="header" class="text-white">{{ $cta_bg['header'] }}</h5>
				@endif
				@if ($cta_bg['txt'])
				<div data-gsap-element="txt" class="text-secondary text-xl mt-1">{!! $cta_bg['txt'] !!}</div>
				@endif
			</div>
			@if (!empty($cta_bg['button']))
			<a data-gsap-element="btn" class="second-btn h-max justify-self-start md:justify-self-end" href="{{ $cta_bg['button']['url'] }}">{{ $cta_bg['button']['title'] }}</a>
			@endif
		</div>

	</div>

</section>