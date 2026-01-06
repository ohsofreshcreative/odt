@php
$sectionClass = '';
$sectionClass .= $flip ? ' order-flip' : '';
$sectionClass .= $wide ? ' wide' : '';
$sectionClass .= $nomt ? ' !mt-0' : '';
$sectionClass .= $gap ? ' wider-gap' : '';
$sectionClass .= $lightbg ? ' section-light' : '';
$sectionClass .= $graybg ? ' section-gray' : '';
$sectionClass .= $whitebg ? ' section-white' : '';
$sectionClass .= $darkbg ? ' section-dark' : '';
$sectionClass .= $brandbg ? ' section-brand' : '';

@endphp

<!--- who -->

<section data-gsap-anim="section" @if(!empty($section_id)) id="{{ $section_id }}" @endif class="b-who relative -smt {{ $sectionClass }} {{ $section_class }}">

	<div class="__wrapper c-main relative z-10">
		<div class="__col grid grid-cols-1 lg:grid-cols-2 gap-10">
			<div class="__first flex flex-col justify-between gap-8">
				<h6 data-gsap-element="title" class="text-primary">{{ $g_who['title'] }}</h6>
				@if (!empty($g_who['image1']))
				<div data-gsap-element="image" class="__img">
					<img class="object-cover w-full __img h-auto radius-img" src="{{ $g_who['image1']['url'] }}" alt="{{ $g_who['image1']['alt'] ?? '' }}">
				</div>
				@endif
			</div>

			<div>
				<div data-gsap-element="header" class="__header m-header">{!! $g_who['header'] !!}</div>
				<div data-gsap-element="txt" class="">
					{!! $g_who['txt'] !!}
				</div>
				@if (!empty($g_who['button']))
				<a data-gsap-element="btn" class="second-btn m-btn" href="{{ $g_who['button']['url'] }}">{{ $g_who['button']['title'] }}</a>
				@endif
			</div>


		</div>

		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-10 pt-10">
			@foreach ($r_who as $item)
			<div data-gsap-element="card" class="__card bg-white radius p-8">
				@if (!empty($item['image']))
				<div class="__img order1">
					<img class="__img radius-img" src="{{ $item['image']['url'] }}" alt="{{ $item['image']['alt'] ?? '' }}">
				</div>
				@endif
				<h6 class="text-h7 text-primary mt-4">{{ $item['header'] }}</h6>
				<p class="mt-2">{{ $item['txt'] }}</p>

			</div>
			@endforeach
		</div>
	</div>

	<img data-gsap-element="bg" class="__bg absolute w-[600px] -bottom-20 -right-20 opacity-50 pointer-events-none" src="/wp-content/uploads/2026/01/shape-about.svg" />
</section>