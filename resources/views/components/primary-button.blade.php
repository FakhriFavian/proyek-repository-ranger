<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-primary icon icon-left']) }}>
    {{ $slot }}
</button>
