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

<!--- cta --->

<section @if(!empty($section_id)) id="{{ $section_id }}" @endif class="b-cta bg-primary-700 radius my-10 {{ $sectionClass }} {{ $section_class }}">
	<div>
		<div class="__wrapper relative radius grid grid-cols-1 lg:grid-cols-2 items-center section-gap z-10">

			<div class="__img relative h-full">
				<img class="h-full w-full object-cover rounded-t-2xl lg:rounded-l-2xl" src="{{ $g_cta['image']['url'] }}" alt="{{ $g_cta['image']['alt'] ?? '' }}" />
				<div class="__overlay absolute inset-0"></div>
			</div>

			<div class="__content p-10">
				<h5 data-gsap-element="header" class="">{{ $g_cta['header'] }}</h5>
				<p data-gsap-element="text" class="text-white mt-6">{!! strip_tags($g_cta['text']) !!}</p>

				@if (!empty($g_cta['button']))
				<x-button
					:href="$g_cta['button']['url']"
					variant="secondary"
					class="mt-6"
					data-gsap-element="btn">
					{{ $g_cta['button']['title'] }}
				</x-button>
				@endif
			</div>

		</div>

		<img class="__bg absolute max-w-[360px] -bottom-16 -right-16 pointer-events-none" src="/wp-content/uploads/2025/12/logo-stroke.svg" />
	</div>
</section>