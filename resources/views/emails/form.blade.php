<h1>{{ $form->title }}</h1>

@foreach ($form->formInputs as $formInput)
@php
$value = data_get($data, $formInput->name);
$value = is_array($value) ? implode(" ,", $value) : $value;
@endphp
<p>
    <span>{{ $formInput->label }} : </span><span>{{ $value }}</span>
</p>
@endforeach
