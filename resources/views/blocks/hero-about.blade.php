@php
$sectionClass = '';
$sectionClass .= $nomt ? ' !mt-0' : '';
@endphp

<!--- hero-about -->

<section data-gsap-anim="section" class="b-hero-about relative z-10 -spt {{ $sectionClass }} {{ $section_class }}">
	<div class="__wrapper c-main relative z-20">
		<div class="__content text-center w-full md:w-3/5 mx-auto mt-18">
			<h1 data-gsap-element="header" class="text-white m-header">{{ $g_heroabout['header'] }}</h1>
			<div data-gsap-element="txt" class="text-white">{!! $g_heroabout['txt'] !!}</div>
		</div>

		<div class="relative">
			<div class="__nr1 absolute right-0 top-0 z-20  -translate-y-1/2 translate-x-20 radius border-5 border-solid border-primary bg-primary-lighter px-6 py-4 lg:translate-x-1/2">
			{!! $g_heroabout_2['txt1'] !!}
			</div>
			<div class="__nr2 absolute left-0 top-full z-20 -translate-x-20 -translate-y-1/2 radius border-5 border-solid border-white bg-secondary text-white px-6 py-4 lg:-translate-x-1/2">
			{!! $g_heroabout_2['txt2'] !!}
			</div>
			@if ($g_heroabout['image'])
			<img data-gsap-element="image" class="__img radius w-full img-l object-cover mt-10" src="{{ $g_heroabout['image']['url'] }}" alt="{{ $g_heroabout['image']['alt'] ?? '' }}">
			@endif
		</div>
	</div>

	<img data-gsap-element="image" class="__bg absolute w-full -top-10 max-w-[400px] -right-10 opacity-20 pointer-events-none z-10" src="/wp-content/uploads/2026/01/top-shape.svg" />

	<img data-gsap-element="image" class="__bg absolute bottom-20 left-0 max-w-[400px] pointer-events-none z-30" src="{{ $g_heroabout['bg']['url'] }}" alt="{{ $g_heroabout['bg']['alt'] ?? '' }}" />
</section>