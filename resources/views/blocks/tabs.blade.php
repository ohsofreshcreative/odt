<!--- tabs --->

@php
  $tabsForJs = [];

  if (!empty($grouped_tabs)) {
      foreach ($grouped_tabs as $name => $items) {
          $tabsForJs[] = [
              'name' => $name,
              'items' => array_map(function ($item) {
                  return [
                      'header' => $item['header'] ?? '',
                      'text'   => $item['text'] ?? '',
                      'image'  => !empty($item['image']) ? [
                          'url' => $item['image']['url'] ?? '',
                          'alt' => $item['image']['alt'] ?? '',
                      ] : null,
                  ];
              }, $items),
          ];
      }
  }
@endphp

<section
  data-gsap-anim="section"
  @if(!empty($section_id)) id="{{ $section_id }}" @endif
  @class([
    'b-tabs relative -smt',
    $sectionClass => !empty($sectionClass),
    $section_class => !empty($section_class),
  ])
>
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

    @if(!empty($tabsForJs))
      <div
        x-data="tabsComponent(@js($tabsForJs))"
        class="__tabs mt-12"
      >
        <div class="swiper tabs-swiper !overflow-visible">
          <div class="swiper-wrapper md:justify-center">
            <template x-for="(tab, index) in tabs" :key="`tab-${index}`">
              <div class="swiper-slide !w-auto">
                <div
                  role="button"
                  tabindex="0"
                  :data-tab-index="index"
                  @click="setTab(index)"
                  @keydown.enter.prevent="setTab(index)"
                  @keydown.space.prevent="setTab(index)"
                  :class="activeTab === index
                    ? 'bg-primary-lighter text-primary border-r border-primary-light'
                    : 'bg-white text-body hover:bg-primary-lighter border-r border-primary-light'"
                  class="relative !font-medium whitespace-nowrap p-6 transition-colors duration-200 focus:outline-none select-none cursor-pointer"
                >
                  <span x-text="tab.name"></span>

                  <div
                    x-show="activeTab === index"
                    x-cloak
                    class="absolute -bottom-2 left-1/2 w-4 h-4 bg-primary -translate-x-1/2 rotate-45 z-50 pointer-events-none"
                  ></div>
                </div>
              </div>
            </template>
          </div>
        </div>

        <div class="__content-wrap mt-6 relative">
          <template x-if="tabs[activeTab]">
            <div
              x-transition:enter="transition-opacity duration-300 ease-out"
              x-transition:enter-start="opacity-0"
              x-transition:enter-end="opacity-100"
              class="space-y-6"
              :key="activeTab"
            >
              <template x-for="(item, itemIndex) in tabs[activeTab].items" :key="`item-${activeTab}-${itemIndex}`">
                <div class="__card bg-white radius grid grid-cols-1 md:grid-cols-2 section-gap items-center p-6 pb-10 md:p-10">
                  <template x-if="item.image && item.image.url">
                    <div class="relative overflow-hidden radius">
                      <img
                        class="w-full img-xl object-cover"
                        :src="item.image.url"
                        :alt="item.image.alt || ''"
                      />
                    </div>
                  </template>

                  <div class="__content relative">
                    <template x-if="item.header">
                      <h6 class="text-body mb-4" x-text="item.header"></h6>
                    </template>

                    <template x-if="item.text">
                      <div class="text-sm" x-html="item.text"></div>
                    </template>

                    {{--
                    <a href="#" class="main-btn mt-4">
                      Dowiedz się więcej
                    </a>
                    --}}
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
        <a
          href="{{ $g_tabs['button']['url'] }}"
          class="main-btn m-btn"
          target="{{ $g_tabs['button']['target'] ?? '_self' }}"
        >
          {{ $g_tabs['button']['title'] }}
        </a>
      </div>
    @endif
  </div>
</section>