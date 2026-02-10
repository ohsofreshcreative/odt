<!--- prices --->
<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-prices relative -spt' ,
	$sectionClass=> !empty($sectionClass),
	$section_class => !empty($section_class),
	])>
	<div class="__wrapper c-main relative z-20">
		@if (!empty($g_prices['header']))
		<div class="text-center mt-10 mb-10">
			<h2 data-gsap-element="header" class="text-white">{{ $g_prices['header'] }}</h2>
			@if(!empty($g_prices['text']))
			<div data-gsap-element="txt" class="__txt text-white max-w-3xl mx-auto mt-4">
				{!! $g_prices['text'] !!}
			</div>
			@endif
		</div>
		@endif

		@if(!empty($grouped_prices))
		<div x-data="{ activeTab: 0 }" data-gsap-element="header" class="__tabs mt-12">

			<div class="swiper prices-swiper !overflow-visible">
				<div class="swiper-wrapper md:justify-center">
					@foreach ($grouped_prices as $name => $items)
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
				@foreach ($grouped_prices as $name => $items)
				<div x-show="activeTab === {{ $loop->index }}" x-cloak
					x-transition:enter="transition ease-out duration-300"
					x-transition:enter-start="opacity-0"
					x-transition:enter-end="opacity-100"
					x-transition:leave="transition ease-in duration-200"
					x-transition:leave-start="opacity-100"
					x-transition:leave-end="opacity-0">
					@foreach ($items as $item)
					<div class="__card relative overflow-hidden bg-white radius grid grid-cols-1 md:grid-cols-[2fr_1fr] section-gap items-center p-6 pb-10 md:p-10">
						<div class="__content relative z-10">
							@if (!empty($item['header']))
							<h6 class="text-body mb-4">{{ $item['header'] }}</h6>
							@endif
							@if (!empty($item['price_rows']))
							<div class="mt-6 overflow-x-auto">
								<table class="w-full text-sm">
									<thead>
										<tr class="text-left">
											<th class="py-2 pr-4">Usługa</th>
											<th class="py-2 pr-4">Czas</th>
											<th class="py-2">Cena</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($item['price_rows'] as $row)
										<tr class="border-t border-primary-light">
											<td class="py-2 pr-4">{{ $row['label'] ?? '' }}</td>
											<td class="py-2 pr-4 whitespace-nowrap">{{ $row['time'] ?? '' }}</td>
											<td class="py-2">{{ $row['price'] ?? '' }}</td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
							@endif

							<div class="flex flex-col justify-between items-center md:flex-row gap-4 mt-6">
								@if (!empty($item['button2']))
								<x-button
									:href="$item['button2']['url']"
									variant="underline"
									class="">
									{{ $item['button2']['title'] }}
								</x-button>
								@endif
								@if (!empty($item['button']))
								<x-button
									:href="$item['button']['url']"
									variant="primary"
									class="">
									{{ $item['button']['title'] }}
								</x-button>
								@endif
							</div>
						</div>

						@if(!empty($item['image']))
						<div class="relative overflow-hidden radius z-10">
							<img data-gsap-element="img" class="w-full object-cover aspect-square" src="{{ $item['image']['url'] }}" alt="{{ $item['image']['alt'] ?? '' }}" />
						</div>
						@endif
						<img data-gsap-element="img" class="absolute -bottom-20 -right-20" src="/wp-content/uploads/2026/01/prices-shape.svg" />
					</div>
					@endforeach
				</div>
				@endforeach
			</div>

		</div>
		@endif

		@if (!empty($g_prices['button']))
		<div class="mt-10 text-center">
			<a href="{{ $g_prices['button']['url'] }}" class="main-btn m-btn" target="{{ $g_prices['button']['target'] ?? '_self' }}">
				{{ $g_prices['button']['title'] }}
			</a>
		</div>
		@endif

		@if(!empty($g_prices2['text']))
		<div data-gsap-element="txt" class="__txt text-center max-w-3xl mx-auto mt-4">
			{!! $g_prices2['text'] !!}
		</div>
		@endif
	</div>

	<img data-gsap-element="image" class="__bg absolute w-full -top-10 max-w-[400px] -right-10 opacity-20 pointer-events-none z-10" src="/wp-content/uploads/2026/01/top-shape.svg" />

	<img data-gsap-element="image" class="__bg absolute top-30 left-0 max-w-[400px] pointer-events-none z-10" src="/wp-content/uploads/2026/01/plant-about.svg" />
</section>