<!--- boxes -->

<section
    data-gsap-anim="section"
    @if(!empty($section_id)) id="{{ $section_id }}" @endif
    @class([ 'b-boxes relative -smt' ,
    $sectionClass=> filled($sectionClass),
    $section_class => filled($section_class),
    $background => filled($background) && $background !== 'none',
    ])>

    <div class="__wrapper c-main relative">
        <div class="__col grid grid-cols-1 lg:grid-cols-2 items-center gap-8 lg:gap-20">
            @if (!empty($g_boxes['image']))
            <div data-gsap-element="img" class="__img h-full order1">
                <img class="object-cover w-full h-full aspect-square __img radius-img" src="{{ $g_boxes['image']['url'] }}" alt="{{ $g_boxes['image']['alt'] ?? '' }}">
            </div>
            @endif

            <div class="__boxes order2 lg:py-10">
                <h4 data-gsap-element="header" class="">{{ $g_boxes['header'] }}</h4>

                <div data-gsap-element="txt" class="__txt mt-4">
                    {!! $g_boxes['txt'] !!}
                </div>

                @if (!empty($g_boxes['boxes']))
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                    @foreach ($g_boxes['boxes'] as $box)
                    <div data-gsap-element="box" class="bg-primary-lighter radius p-6">
                        @if (!empty($box['header']))
                        <h4 class="text-action">{{ $box['header'] }}</h4>
                        @endif
                        @if (!empty($box['txt']))
                        <p class="text-action mt-1">{{ $box['txt'] }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif

                @if (!empty($g_boxes['hint']))
                <div data-gsap-element="box" class="__hint flex items-center radius bg-primary-lighter border border-dashed border-primary p-6 gap-4 mt-6">
                    @if (!empty($g_boxes['image_hint']['url']))
                    <img
                        class="max-w-10 aspect-square"
                        src="{{ $g_boxes['image_hint']['url'] }}"
                        alt="{{ $g_boxes['image_hint']['alt'] ?? '' }}">
                    @endif

                    @if (!empty($g_boxes['header_hint']))
                    <div class="">
                        {{ $g_boxes['header_hint'] }}
                    </div>
                    @endif
                </div>
                @endif

                @if (!empty($g_boxes['button']))
                <x-button
                    :href="$g_boxes['button']['url']"
                    variant="primary"
                    class="mt-6"
                    data-gsap-element="btn">
                    {{ $g_boxes['button']['title'] }}
                </x-button>
                @endif

            </div>

        </div>
    </div>

</section>