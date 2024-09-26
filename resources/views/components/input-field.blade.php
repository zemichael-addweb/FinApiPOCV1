@props([
    'type' => 'text',  // Default type is text
    'name',            // Field name (required)
    'label' => '',     // Optional label
    'value' => '',     // Default value for input
    'placeholder' => '', // Optional placeholder
    'required' => false,  // If field is required
    'disabled' => false,  // If field is disabled
    'readonly' => false,  // If field is readonly
    'error' => null,      // Error message (optional)
    'class' => '',        // Custom classes
    'id' => null,         // Optional id
])

<div class="mb-4">
    @if($label)
        <label for="{{ $id ?? $name }}" class="block text-slate-700 dark:text-slate-100 text-sm font-bold mb-2">
            {{ $label }}
        </label>
    @endif

    <input 
        type="{{ $type }}" 
        name="{{ $name }}" 
        id="{{ $id ?? $name }}" 
        value="{{ old($name, $value) }}" 
        placeholder="{{ $placeholder }}" 
        class="shadow appearance-none border rounded w-full py-2 px-3 text-slate-700 dark:text-slate-50 bg-slate-100 dark:bg-slate-800 leading-tight focus:outline-none focus:shadow-outline {{ $error ? 'border-red-500' : '' }} {{ $class }}"
        {{ $required ? 'required' : '' }} 
        {{ $disabled ? 'disabled' : '' }} 
        {{ $readonly ? 'readonly' : '' }}
    />

    @if($error)
        <p class="text-red-500 text-xs italic mt-2">{{ $error }}</p>
    @endif
</div>
