@php
    $baseClasses = 'inline-flex items-center px-4 py-2 font-medium rounded-lg transition duration-150 ease-in-out';
    
    if ($attributes->has('outline')) {
        $baseClasses .= ' border-2 border-current text-gray-700 hover:bg-gray-50';
    } elseif ($attributes->has('gray')) {
        $baseClasses .= ' bg-gray-200 text-gray-800 hover:bg-gray-300';
    } else {
        $baseClasses .= ' bg-blue-600 text-white hover:bg-blue-700';
    }
    
    if ($attributes->has('disabled')) {
        $baseClasses .= ' opacity-50 cursor-not-allowed';
    }

    // Get additional classes from the class attribute if present
    $additionalClasses = $attributes->get('class', '');
    $finalClasses = $baseClasses . ('' !== $additionalClasses ? ' ' . $additionalClasses : '');
@endphp

@if ($attributes->has('href'))
    <a href="{{ $attributes->get('href') }}" {{ $attributes->class($finalClasses)->except(['href', 'outline', 'gray', 'disabled']) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $attributes->get('type', 'button') }}" {{ $attributes->class($finalClasses)->except(['type', 'outline', 'gray', 'disabled']) }}>
        {{ $slot }}
    </button>
@endif
