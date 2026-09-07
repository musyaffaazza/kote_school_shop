<div>
    <label for="{{ $id ?? $name }}" class="mb-2 block text-sm font-semibold text-[#21140b]">{{ $label ?? '' }}</label>
    <div class="relative">
        @isset($icon)
            <span class="absolute left-4 top-1/2 -translate-y-1/2 flex items-center justify-center text-[#8f7664] pointer-events-none">{{ $icon }}</span>
        @endisset

        <input
            id="{{ $id ?? $name }}"
            name="{{ $name }}"
            type="{{ $type ?? 'text' }}"
            value="{{ $value ?? old($name) }}"
            placeholder="{{ $placeholder ?? '' }}"
            {{ $attributes->merge(['class' => trim((isset($icon) ? 'pl-11 ':'').' '.(isset($rightIcon) ? 'pr-11 ':'').' min-h-[48px] w-full rounded-xl border border-[#e7d7ce] bg-[#fbf1e8] px-4 py-3 text-sm text-[#21140b] placeholder:text-[#b79f8d] shadow-sm transition focus:border-[#c5ab98] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c5ab98]/20')]) }}
        />

        @isset($rightIcon)
            @if(($type ?? 'text') === 'password')
                <button type="button" data-password-target="{{ $id ?? $name }}" class="js-password-toggle absolute right-3.5 top-1/2 -translate-y-1/2 flex items-center justify-center text-[#8f7664] hover:text-[#21140b] transition-colors p-1 rounded-md cursor-pointer focus:outline-none">
                    {!! $rightIcon !!}
                </button>
            @else
                <span class="absolute right-4 top-1/2 -translate-y-1/2 flex items-center justify-center text-[#8f7664] pointer-events-none">{{ $rightIcon }}</span>
            @endif
        @endisset
    </div>

    @if ($error)
        <p class="mt-1.5 text-sm text-red-600">{{ $error }}</p>
    @endif
</div>
