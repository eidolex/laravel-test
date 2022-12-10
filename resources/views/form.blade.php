<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $form->title }}</title>
</head>
<body>
    <h1>{{ $form->title }}</h1>
    <form id="form" method="POST">
    @csrf
    <input type="hidden" name="form_id" value="{{ $form->id }}">
    @foreach ($form->formInputs as $formInput)
        <x-custom-input :formInput="$formInput"></x-custom-input>
    @endforeach
    <button type="submit">Submit</button>
    </form>
    <script>
        (function() {
            const form = document.getElementById('form');

            if (form) {
                form.addEventListener('submit', (event) => {
                    event.preventDefault();
                    const data = new FormData(form);
                    fetch("{{ route('form.submit') }}", {
                        method: "POST",
                        body: data,
                        headers: {
                            accept: 'applicatipn/json',
                        }
                    })
                        .then(res => res.json())
                        .then(data => alert(data.message));
                });
            }

        })();
    </script>
</body>
</html>
