@php
$sectionClass = '';
$sectionClass .= !empty($flip) ? ' order-flip' : '';
$sectionClass .= !empty($wide) ? ' wide' : '';
$sectionClass .= !empty($nomt) ? ' !mt-0' : '';
$sectionClass .= !empty($gap) ? ' wider-gap' : '';

if (!empty($background) && $background !== 'none') {
    $sectionClass .= ' ' . $background;
}

// --- Dynamic Grid Logic ---
$itemCount = count($g_numbers['r_numbers'] ?? []);
$gridClass = 'grid-cols-1'; // Default for mobile

// Set grid columns for medium screens and up based on item count
if ($itemCount > 1) {
    $gridClass .= ' md:grid-cols-' . min($itemCount, 5); // Handles 2, 3, 4, 5 items
}
// --- End Dynamic Grid Logic ---
@endphp

<!--- numbers --->

<section data-gsap-anim="section" @if(!empty($section_id)) id="{{ $section_id }}" @endif class="b-numbers -smt {{ $sectionClass }} {{ $section_class ?? '' }}">

    <div class="__wrapper c-main">
        <div class="">
            <div class="grid grid-cols-1 md:grid-cols-[1fr_2fr_1fr] gap-8">
                @if (!empty($g_numbers['header']))
                <p data-gsap-element="header" class="font-header text-h4">{{ strip_tags($g_numbers['header']) }}</p>
                @endif
                @if (!empty($g_numbers['txt']))
                <div data-gsap-element="txt" class="mt-2">
                    {!! $g_numbers['txt'] !!}
                </div>
                @endif
                @if (!empty($g_numbers['image']))
                <img data-gsap-element="txt" class="" src="{{ $g_numbers['image']['url'] }}" alt="{{ $g_numbers['image']['alt'] ?? '' }}">
                @endif
            </div>

            @if (!empty($g_numbers['r_numbers']))
            <div class="grid {{ $gridClass }} gap-8 border-top-p mt-14">
                @foreach ($g_numbers['r_numbers'] as $item)
                <div data-gsap-element="card" class="__card relative bg-primary-lighter radius p-6">
                    @if (!empty($item['title']))
                    <p class="text-h2">{{ $item['title'] }}</p>
                    @endif
                    @if (!empty($item['txt']))
                    <p class="">{{ $item['txt'] }}</p>
                    @endif
                </div>
                @endforeach
            </div>
            @endif

        </div>
    </div>

</section>