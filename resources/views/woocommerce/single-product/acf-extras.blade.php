
@php $product = wc_get_product(get_the_ID());
 $attributes = $product->get_attributes();
@endphp@if (!empty($attributes))
 <section class="product-extras pt-6 mt-6 space-y-3 y-border-t">
 @foreach ($attributes as $attribute)
 @php $name = '';
 $text = '';

 if ($attribute instanceof \WC_Product_Attribute) {
 $name = wc_attribute_label($attribute->get_name());

 if ($attribute->is_taxonomy()) {
 $values = wc_get_product_terms($product->get_id(), $attribute->get_name(), ['fields' => 'names']);
 } else {
 $values = $attribute->get_options();
 }

 $text = !empty($values) ? implode(', ', array_map('esc_html', $values)) : '';
 }
 @endphp @if (!empty($name) && !empty($text)) {{-- Pomijaj puste atrybuty --}}
 <article class="product-extra">
 <div class="product-extra__header">
 <h6 class="font-semibold">{{ esc_html($name) }}</h6>
 </div>
 <div class="product-extra__content">
 {!! wp_kses_post($text) !!}
 </div>
 </article>
 @endif @endforeach </section>
@endif