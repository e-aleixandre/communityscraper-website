@component('mail::message')
# Informe fallido

Hola,

El informe que solicitaste ha sufrido un error y no se ha generado correctamente. Puedes revisar el listado en la sección de [Informes](https://communityscraper.es/reports), donde puedes intentar relanzarlo o eliminarlo.

Hasta pronto,<br>
{{ config('app.name') }}

@endcomponent
