<div>
    <H2>Te invitamos a participar en la oferta de carreras técnicas</H2>
    Hola
    @if ($bachiller->sexo == 'Hombre')
        estimado
    @else
        estimada
    @endif
    {{ $bachiller->primer_nombre }} {{ $bachiller->primer_apellido }}, queremos hacerte partícipe de la nueva oferta de carreras...
    <BR/>
    <A href="{{ url('register') }}" target="_blank">Regístrate</A>
</div>
