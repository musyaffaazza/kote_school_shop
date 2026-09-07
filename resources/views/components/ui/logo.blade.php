@props([
    'class' => 'h-8 w-auto',
    'variant' => 'default',
])

@php
    $logoPaths = [
        'images/logo.png',
        'images/logo.svg',
        'images/logo.jpg',
        'images/logo.webp',
    ];
    
    $existingLogo = null;
    foreach ($logoPaths as $path) {
        if (file_exists(public_path($path))) {
            $existingLogo = asset($path);
            break;
        }
    }
@endphp

@if ($existingLogo)
    <img src="{{ $existingLogo }}" alt="KOTE SCHOOL SHOP Logo" {{ $attributes->merge(['class' => $class . ' object-contain']) }} />
@else
    {{-- Fallback SVG --}}
    @if ($variant === 'splash')
        <svg width="130" height="140" viewBox="0 0 120 130" fill="none" xmlns="http://www.w3.org/2000/svg" {{ $attributes->merge(['class' => 'text-[#21140b] ' . $class]) }}>
            <path d="M34 52C24 49 19 37 21 27C27 29 37 39 34 52Z" fill="#3d2a1f"/>
            <path d="M27 62C17 65 14 55 19 45C23 51 31 55 27 62Z" fill="#3d2a1f"/>
            <circle cx="21" cy="55" r="2.5" fill="#a2785d"/>
            <circle cx="25" cy="59" r="2" fill="#a2785d"/>
            <path d="M46 22V90M46 56L84 22M58 50L88 90" stroke="#21140b" stroke-width="8.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M50 66H76C76 76 68 83 60 83C54 83 50 76 50 66Z" fill="#21140b"/>
            <path d="M76 70H81C83 70 84 73 81 76H76" stroke="#21140b" stroke-width="2.5"/>
            <path d="M58 60C60 56 56 54 58 50" stroke="#a2785d" stroke-width="2" stroke-linecap="round"/>
            <path d="M66 62C68 58 64 56 66 52" stroke="#a2785d" stroke-width="2" stroke-linecap="round"/>
            <text x="60" y="112" font-family="serif" font-size="22" font-weight="bold" fill="#21140b" text-anchor="middle" letter-spacing="1">Kote</text>
            <line x1="22" y1="122" x2="38" y2="122" stroke="#21140b" stroke-width="1.5"/>
            <text x="60" y="125" font-family="sans-serif" font-size="8.5" font-weight="bold" fill="#21140b" text-anchor="middle" letter-spacing="2">COFFEE</text>
            <line x1="82" y1="122" x2="98" y2="122" stroke="#21140b" stroke-width="1.5"/>
        </svg>
    @else
        <svg width="28" height="36" viewBox="0 0 28 36" fill="none" xmlns="http://www.w3.org/2000/svg" {{ $attributes->merge(['class' => $class . ' text-[#21140b]']) }}>
            <path d="M4 2V28M4 15L17 2M9 12L19 28" stroke="#21140b" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round"/>
            <text x="3" y="35" font-family="serif" font-size="7.5" font-style="italic" fill="#21140b" letter-spacing="0.5">kote</text>
        </svg>
    @endif
@endif
