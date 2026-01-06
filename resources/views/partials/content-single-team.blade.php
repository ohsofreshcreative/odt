@php
$categories = get_the_category();
$category = !empty($categories) ? $categories[0] : null;
@endphp


<section>
	{!! the_content() !!}
</section>
