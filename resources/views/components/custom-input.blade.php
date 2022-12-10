<div style="margin-bottom: 0.5rem;">
    @if (array_search($formInput->input_type, ['radio', 'checkbox']) > -1)
        <span>{{ $formInput->label }}</span>
    @else
        <label for="{{ $inputId }}">{{ $formInput->label }}</label>
    @endif

    @if ($formInput->input_type == 'textarea')
        <textarea id="{{ $inputId }}" name="{{ $inputName }}"></textarea>
    @elseif ($formInput->input_type == 'select')
        <select id="{{ $inputId }}" name="{{ $inputName }}">
            @foreach ($formInput->choices as $key => $choice)
            <option value="{{ $choice['value'] }}">{{ $choice['label'] }}<option>
            @endforeach
        </select>
    @elseif ($formInput->input_type == 'checkbox' || $formInput->input_type == 'radio')
        @foreach ($formInput->choices as $key => $choice)
            <input id="{{ $inputId }}_{{$key}}" type="{{ $formInput->input_type }}" value="{{ $choice['value'] }}" name="{{ $inputName }}">
            <label for="{{ $inputId }}_{{$key}}">{{ $choice['label'] }}</label>
        @endforeach
    @else
    <input id="{{ $inputId }}" type="{{ $formInput->input_type }}" name="{{ $inputName }}">
    @endif
</div>
