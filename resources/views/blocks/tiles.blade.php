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

<!--- tiles -->

<section data-gsap-anim="section" @if(!empty($section_id)) id="{{ $section_id }}" @endif class="b-tiles relative -smt overflow-hidden {{ $sectionClass }} {{ $section_class }}">

	<div class="__wrapper c-main relative z-10">

		<h3 data-gsap-element="header" class="w-full md:w-1/2">{{ $g_tiles['header'] }}</h3>

		<div class="__col grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-16 mt-6 lg:mt-10">

			<div class="mr-0 md:mr-10">
				<div data-gsap-element="txt" class="__txt mt-2">
					{!! $g_tiles['txt'] !!}
				</div>

				@if (!empty($g_tiles['image']))
				<img data-gsap-element="image" class="object-cover w-full img-m radius-img mt-6" src="{{ $g_tiles['image']['url'] }}" alt="{{ $g_tiles['image']['alt'] ?? '' }}">
				@endif

				@if (!empty($g_tiles['button']))
				<div class="inline-buttons m-btn">
					<a data-gsap-element="button" class="second-btn left-btn"
						href="{{ $g_tiles['button']['url'] }}"
						target="{{ $g_tiles['button']['target'] }}">
						{{ $g_tiles['button']['title'] }}
					</a>
					@if (!empty($g_tiles['button2']))
					<a data-gsap-element="button" class="white-btn"
						href="{{ $g_tiles['button2']['url'] }}"
						target="{{ $g_tiles['button2']['target'] }}">
						{{ $g_tiles['button2']['title'] }}
					</a>
					@endif
				</div>
				@endif
			</div>

			<div class="grid gap-10">
				@foreach ($r_tiles as $item)
				<div data-gsap-element="card" class="__card">
					<div class="__content flex items-start gap-6">
						@if (!empty($item['image']))
						<img class="max-w-18 radius-img" src="{{ $item['image']['url'] }}" alt="{{ $item['image']['alt'] ?? '' }}">
						@endif
						<div>
							<h6 class="text-h7">{{ $item['header'] }}</h6>
							<p class="text-white mt-2">{!! $item['txt'] !!}</p>
						</div>
					</div>
				</div>
				@endforeach
			</div>
		</div>
	</div>

	<img data-gsap-element="bg" class="__bg absolute w-[500px] -left-20 top-20 pointer-events-none" src="{{ $g_tiles['bg1']['url'] }}" />
	<img data-gsap-element="bg" class="__bg absolute w-[400px] top-1/2 -translate-y-1/2 -right-20 pointer-events-none" src="{{ $g_tiles['bg2']['url'] }}" />
</section>