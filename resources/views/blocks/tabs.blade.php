<!--- tabs --->
<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-tabs relative -smt' ,
	$sectionClass=> !empty($sectionClass),
	$section_class => !empty($section_class),
	])>
	<div class="__wrapper c-main relative">
		@if (!empty($g_tabs['header']))
		<div class="mb-10 text-center">
			<h2 data-gsap-element="header">{{ $g_tabs['header'] }}</h2>
			@if(!empty($g_tabs['text']))
			<div class="__txt mt-4 max-w-3xl mx-auto">
				{!! $g_tabs['text'] !!}
			</div>
			@endif
		</div>
		@endif

		@if(!empty($grouped_tabs))
		<div x-data="{ activeTab: 0 }" class="__tabs mt-12">

			<div class="swiper tabs-swiper !overflow-visible">
				<div class="swiper-wrapper md:justify-center">
					@foreach ($grouped_tabs as $name => $items)
					<div class="swiper-slide !w-auto">
						<div
							role="button"
							tabindex="0"
							data-tab-index="{{ $loop->index }}"
							@click="activeTab = {{ $loop->index }}"
							@keydown.enter="activeTab = {{ $loop->index }}"
							@keydown.space.prevent="activeTab = {{ $loop->index }}"
							:class="{ 'bg-primary-lighter text-primary border-r border-primary-light': activeTab === {{ $loop->index }}, 'bg-white text-body hover:bg-primary-lighter border-r border-primary-light': activeTab !== {{ $loop->index }} }"
							class="relative !font-medium whitespace-nowrap p-6 transition-colors duration-200 focus:outline-none select-none cursor-pointer">
							{{ $name }}

							<div x-show="activeTab === {{ $loop->index }}" x-cloak
								class="absolute -bottom-2 left-1/2 w-4 h-4 bg-primary transform -translate-x-1/2 rotate-45 z-60 pointer-events-none">
							</div>
						</div>
					</div>
					@endforeach
				</div>
			</div>

			<div class="">
				@foreach ($grouped_tabs as $name => $items)
				<div x-show="activeTab === {{ $loop->index }}" x-cloak
					x-transition:enter="transition ease-out duration-300"
					x-transition:enter-start="opacity-0"
					x-transition:enter-end="opacity-100"
					x-transition:leave="transition ease-in duration-200"
					x-transition:leave-start="opacity-100"
					x-transition:leave-end="opacity-0">
					@foreach ($items as $item)
					<div class="__card bg-white radius grid grid-cols-1 md:grid-cols-2 section-gap items-center p-6 pb-10 md:p-10">
						@if(!empty($item['image']))
						<div class="relative overflow-hidden radius">
							<img class="w-full img-xl object-cover" src="{{ $item['image']['url'] }}" alt="{{ $item['image']['alt'] ?? '' }}" />
						</div>
						@endif
						<div class="__content relative ">
							@if (!empty($item['header']))
							<h6 class="text-body mb-4">{{ $item['header'] }}</h6>
							@endif
							@if (!empty($item['text']))
							<div class="text-sm">{{ $item['text'] }}</div>
							@endif
							<a href="#" class="main-btn mt-4">
								Dowiedz się więcej
							</a>
						</div>
					</div>
					@endforeach
				</div>
				@endforeach
			</div>

		</div>
		@endif

		@if (!empty($g_tabs['button']))
		<div class="mt-10 text-center">
			<a href="{{ $g_tabs['button']['url'] }}" class="main-btn m-btn" target="{{ $g_tabs['button']['target'] ?? '_self' }}">
				{{ $g_tabs['button']['title'] }}
			</a>
		</div>
		@endif
	</div>
</section>