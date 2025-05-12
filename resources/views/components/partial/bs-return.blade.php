@props(['text' => 'Volver', 'class' => '', 'icon' => true, 'route' => null])

<div {{ $attributes->merge(['class' => 'mb-3 ' . $class]) }}>
    @if($route)
        <a href="{{ route($route) }}" class="btn btn-outline-secondary d-inline-flex align-items-center">
            @if($icon)
            <i class="bi bi-arrow-left me-2"></i>
            @endif
            {{ $text }}
        </a>
    @else
        <button onclick="window.history.back();" class="btn btn-outline-secondary d-inline-flex align-items-center">
            @if($icon)
            <i class="bi bi-arrow-left me-2"></i>
            @endif
            {{ $text }}
        </button>
    @endif
</div>