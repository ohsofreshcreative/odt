@php
$block_to_render = null;

if ($source_page_id && $block_name) {
$source_content = get_post_field('post_content', $source_page_id);
$source_blocks = parse_blocks($source_content);

foreach ($source_blocks as $block) {
if ($block['blockName'] === $block_name) {
$block_to_render = $block;
break;
}
}
}

$sectionClass = '';
if (!empty($background) && $background !== 'none') {
$sectionClass .= ' ' . $background;
}
@endphp

<section @if(!empty($section_id)) id="{{ $section_id }}" @endif class="b-block-clone -smt {{ $sectionClass }} {{ $section_class ?? '' }}">

	@if (is_admin())
	<div style="border: 2px dashed #0073aa; padding: 15px; text-align: center; margin: 20px 0;">
		<p><strong>Klon bloku:</strong> <code>{{ $block_name ?? 'Nie podano nazwy' }}</code></p>
		<p><strong>Ze strony:</strong> {{ $source_page_id ? get_the_title($source_page_id) : 'Nie wybrano strony' }}</p>
		@if (!$block_to_render && $source_page_id && $block_name)
		<p style="color: red;"><strong>Błąd:</strong> Nie znaleziono bloku o tej nazwie na wybranej stronie.</p>
		@endif
	</div>
	@endif

	@if ($block_to_render)
	{!! render_block($block_to_render) !!}
	@endif
</section>