<!--- faq --->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-faq relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main grid grid-cols-1 md:grid-cols-[1fr_2.5fr] gap-0 md:gap-20">

		<div class="__content">
			<h3 data-gsap-element="header" class="">{{ $g_faq['header'] }}</h3>
			@if (!empty($g_faq['image']))
			<div data-gsap-element="img" class="__img order1 mt-10">
				<img class="__img absolute bottom-10 left-1/2 -translate-x-1/2" src="/wp-content/uploads/2026/01/faq_blurb.svg">
				<img class="__img object-cover aspect-square rounded-full" src="{{ $g_faq['image']['url'] }}" alt="{{ $g_faq['image']['alt'] ?? '' }}">
			</div>
			@endif
		</div>
		<div data-gsap-element="accordion" class="accordion-wrapper flex flex-col mt-4">
			@foreach ($r_faq as $item)
			<div class="accordion rounded-2xl bg-white border border-secondary h-max">
				<input class="acc-check" type="checkbox" name="radio-a" id="check{{ $loop->index }}">
				<label class="accordion-label flex items-center justify-between" for="check{{ $loop->index }}">
					<div class="flex items-center gap-4">
						<h6 class="!text-lg">{{ $item['title'] }}</h6>
					</div>
					<x-icon.arrow-up class="__arrow text-secondary w-3 h-4" />
				</label>
				<div class="accordion-content">
					<p>{!! $item['txt'] !!}</p>
				</div>
			</div>
			@endforeach
		</div>

	</div>

</section>