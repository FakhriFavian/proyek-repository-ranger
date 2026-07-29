@props(['name', 'field'])

@php
    $type = $field['type'] ?? 'text';
    $id = $field['id'] ?? $name;
    $value = $field['value'] ?? null;
    $placeholder = $field['placeholder'] ?? '';
    $required = $field['required'] ?? false;
    $disabled = $field['disabled'] ?? false;
    $multiple = $field['multiple'] ?? false;
    $class = trim('form-control '.($field['class'] ?? ''));
    $fieldName = $multiple ? $name.'[]' : $name;
@endphp

@switch($type)
    @case('select')
        <select name="{{ $fieldName }}" id="{{ $id }}" class="{{ $class }}"
            @if($required) required @endif
            @if($disabled) disabled @endif
            @if($multiple) multiple @endif>
            @if($placeholder)
                <option value="">{{ $placeholder }}</option>
            @endif
            @foreach (($field['options'] ?? []) as $optValue => $optLabel)
                @php
                    $selected = $multiple
                        ? (is_array($value) && in_array($optValue, $value))
                        : ((string) $value === (string) $optValue);
                @endphp
                <option value="{{ $optValue }}" @selected($selected)>{{ $optLabel }}</option>
            @endforeach
        </select>
        @break

    @case('textarea')
        <textarea name="{{ $fieldName }}" id="{{ $id }}" class="{{ $class }}" placeholder="{{ $placeholder }}"
            @if($required) required @endif
            @if($disabled) disabled @endif>{{ $value }}</textarea>
        @break

    @case('password')
        <input type="password" name="{{ $fieldName }}" id="{{ $id }}" class="{{ $class }}" placeholder="{{ $placeholder }}"
            @if($required) required @endif
            @if($disabled) disabled @endif>
        @break

    @case('hidden')
        <input type="hidden" name="{{ $fieldName }}" id="{{ $id }}" value="{{ $value }}">
        @break

    @case('number')
        <input type="number" name="{{ $fieldName }}" id="{{ $id }}" class="{{ $class }}" value="{{ $value }}" placeholder="{{ $placeholder }}"
            @if($required) required @endif
            @if($disabled) disabled @endif>
        @break

    @default
        <input type="text" name="{{ $fieldName }}" id="{{ $id }}" class="{{ $class }}" value="{{ $value }}" placeholder="{{ $placeholder }}"
            @if($required) required @endif
            @if($disabled) disabled @endif>
@endswitch
