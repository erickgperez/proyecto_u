<div>
    @if ($bachiller->sexo == 'Hombre')
        estimado
    @else
        estimada
    @endif
    {{ $bachiller->primer_nombre }} {{ $bachiller->primer_apellido }}
    <BR/>
    {{ $convocatoria->cuerpo_mensaje }}
    <BR/>
    <A href="{{ url('register') }}" target="_blank">Regístrate</A>
</div>
