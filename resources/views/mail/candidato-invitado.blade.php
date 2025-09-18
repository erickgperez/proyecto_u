<!DOCTYPE html>
    <html>
    <head>
        <!--<title>My HTML Email</title>-->
        <style>
            /* Your CSS styles here */
            body { font-family: sans-serif; }
            .container { padding: 20px; }
            .header { background-color: #f0f0f0; padding: 10px; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1>Bienvenido!</h1>
            </div>
            <div>
                @if ($bachiller->sexo == 'Hombre')
                    Estimado
                @else
                    Estimada
                @endif
                {{ $bachiller->primer_nombre }} {{ $bachiller->primer_apellido }}
                <BR/>

                {!! $convocatoria->cuerpo_mensaje !!}
                <BR/>
                <A href="{{ config('app.url') }}/register" target="_blank">Regístrate</A>
            </div>
        </div>
    </body>
    </html>
