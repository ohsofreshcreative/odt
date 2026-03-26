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
        <div x-data="{ activeTab: 0 }"
             x-init="
                $watch('activeTab', () => {
                    $nextTick(() => {
                        let h = $refs['tabPanel' + activeTab].offsetHeight;
                        $refs.tabContainer.style.minHeight = h + 'px';
                    });
                });
                $nextTick(() => {
                    let h = $refs['tabPanel' + activeTab].offsetHeight;
                    $refs.tabContainer.style.minHeight = h + 'px';
                });
             "
             class="__tabs mt-12">

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

           <div class="mt-6 relative">
	<template x-if="tabs[activeTab]">
		<div
			x-transition.opacity.duration.300ms
			class="space-y-6"
		>
			<template x-for="(item, itemIndex) in tabs[activeTab]" :key="itemIndex">
				<div class="__card bg-white radius grid grid-cols-1 md:grid-cols-2 section-gap items-center p-6 pb-10 md:p-10">
					<template x-if="item.image">
						<div class="relative overflow-hidden radius">
							<img class="w-full img-xl object-cover" :src="item.image.url" :alt="item.image.alt || ''">
						</div>
					</template>

					<div class="__content relative">
						<template x-if="item.header">
							<h6 class="text-body mb-4" x-text="item.header"></h6>
						</template>

						<template x-if="item.text">
							<div class="text-sm" x-html="item.text"></div>
						</template>
					</div>
				</div>
			</template>
		</div>
	</template>
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