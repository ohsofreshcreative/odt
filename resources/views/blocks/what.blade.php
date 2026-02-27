@php
$sectionClass = '';
$sectionClass .= $nomt ? ' !mt-0' : '';
@endphp

<!--- what -->

<section data-gsap-anim="section" class="b-what -smt relative overflow-hidden {{ $sectionClass }} {{ $section_class }}">

	<div class="bg-gradient-light rounded-4xl overflow-hidden mx-6 sm:mx-6 md:mx-10 lg:mx-20">

		<div class="__wrapper c-main text-center relative z-20 py-40">
			<img src="{{ $what['image']['url'] }}" alt="{{ $what['image']['alt'] }}" class="__img1 absolute bottom-6 md:bottom-20 left-2/12 w-30 pointer-events-none z-10" />

			<img src="{{ $what['image2']['url'] }}" alt="{{ $what['image2']['alt'] }}" class="__img1 absolute top-6 md:top-20 right-2/12 w-30 pointer-events-none z-10" />

			<div class="relative w-full z-10 md:w-1/2 mx-auto">
				@if ($what['header'])
				<h4 data-gsap-element="header" class="text-white m-header">{{ $what['header'] }}</h4>
				@endif
				@if ($what['txt'])
				<div data-gsap-element="txt" class="text-white">{!! $what['txt'] !!}</div>
				@endif
				@if (!empty($what['button']))
				<x-button
					:href="$what['button']['url']"
					variant="secondary"
					class="mt-6"
					data-gsap-element="btn">
					{{ $what['button']['title'] }}
				</x-button>
				@endif
			</div>

		<img class="__bg absolute top-1/2 -translate-y-1/2 opacity-50 pointer-events-none" src="/wp-content/uploads/2026/01/leaf-big2.svg" />

		<img class="__bg absolute top-1/2 -translate-y-1/2 -right-90 opacity-20 pointer-events-none" src="/wp-content/uploads/2026/01/leaf-big.svg" />
		</div>
	</div>
</section>