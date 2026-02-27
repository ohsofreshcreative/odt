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

<!--- about -->

<section data-gsap-anim="section" @if(!empty($section_id)) id="{{ $section_id }}" @endif class="b-about relative -smt {{ $sectionClass }} {{ $section_class }}">

	<div class="__wrapper c-main relative z-10">
		<div class="__col grid grid-cols-1 lg:grid-cols-2 gap-10">
			@if (!empty($g_about['image']))

			<div data-gsap-element="img" class="__img relative h-full order1 break-out mt-0 lg:mt-20">
				<img class="__bg spinning-logo bg-white rounded-full overflow-visible absolute 0 pointer-events-none z-10 -top-14 left-14 p-3" src="/wp-content/uploads/2026/01/logo_circle.svg" />
				<img class="__photo object-cover w-full img-xl __img radius-img" src="{{ $g_about['image']['url'] }}" alt="{{ $g_about['image']['alt'] ?? '' }}">
			</div>
			@endif

			<div class="__about order2 pb-0 lg:pb-40">

				<div data-gsap-element="txt" class="__txt mt-2">
					{!! $g_about['txt'] !!}
				</div>

				@if (!empty($g_about['button']))
				<a data-gsap-element="btn" class="main-btn m-btn align-self-bottom" href="{{ $g_about['button']['url'] }}">{{ $g_about['button']['title'] }}</a>
				@endif

			</div>

		</div>
	</div>

	<img data-gsap-element="bg" class="__bg absolute w-[500px] -left-20 -bottom-40 pointer-events-none" src="/wp-content/uploads/2026/01/shape_light.svg" />
</section>