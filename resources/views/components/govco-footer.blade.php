@push('styles')
    <link rel="stylesheet" href="{{ asset('css/components/govco-footer.css') }}">
@endpush

<div class="govco-footer">
    <div class="govco-data-front">
        <div class="govco-footer-text">
            <div class="row govco-nombre-entidad">
                <div class="col-xs-12 col-lg-6">
                    <p class="govco-text-header-1">Empresa de servicios públicos de Cajicá</p>
                </div>
                <div class="col-xs-12 col-lg-5 govco-logo-div-a">
                    <span class="govco-logo-entidad"></span>
                </div>
            </div>

            <div class="row col-xs-12 col-lg-7 govco-texto-sedes">
            <p class="govco-text-header-2">Sede principal</p>
            <strong>Dirección:</strong>
            Calle 3 Sur No. 1 – 07<br class="govco-mostrar">
            Cajicá, Cundinamarca, Colombia.<br>
            Código Postal: 250240<br><br>
            <strong>Horario de atención:</strong><br>
            Lunes a Jueves: 7:00 am - 4:00 pm (Jornada continua)<br>
            Viernes: 7:00 am - 3:00 pm<br><br>
            <strong>Contacto:</strong><br>
            Línea Atención Usuario: (+57) 310 565 6959 <br>
            Línea anticorrupción: (+57) 321 286 1900 <br>
            Correo institucional: empresa_epc@epccajica.gov.co <br>
            Correo atención usuario: Atencion.ususario@epccajica.gov.co <br>
            Notificaciones judiciales: empresa_epc@yahoo.es <br><br>
            <strong>Emergencias 24H:</strong><br>
            Acueducto: 3114815558 | Aseo: 3114809319 | Alcantarillado: 3107981343
            </div>

            <div class="row col-xs-12 col-lg-7 govco-network">
            {{-- Redes sociales actualizadas --}}
            <div class="govco-iconContainer">
                <span class="icon govco-twitter-square"></span>
                <a class="govco-link-modal" href="https://x.com/epccajicoficial" target="_blank" rel="noopener noreferrer">@epccajicoficial</a>
            </div>
            <div class="govco-iconContainer">
                <span class="icon govco-instagram-square"></span>
                <a class="govco-link-modal" href="https://www.instagram.com/epccajica/?igsh=a2UwNHppZHphNWZp" target="_blank" rel="noopener noreferrer">epccajica</a>
            </div>
            <div class="govco-iconContainer">
                <span class="icon govco-facebook-square"></span>
                <a class="govco-link-modal" href="https://web.facebook.com/epccajica?mibextid=ZbWKwL&_rdc=1&_rdr" target="_blank" rel="noopener noreferrer">epccajica</a>
            </div>
            <div class="govco-iconContainer">
                <span class="icon govco-youtube-square"></span> {{-- Asumiendo que existe icono youtube en la fuente govco --}}
                <a class="govco-link-modal" href="https://www.youtube.com/@epcajica" target="_blank" rel="noopener noreferrer">epccajica</a>
            </div>
            </div>

            {{-- Enlaces inferiores actualizados --}}
            {{-- <div class="row govco-links-directorio">
            <a class="govco-link-modal" href="#">Directorio Institucional</a>  --}}
            {{-- Comentado si no aplica --}}
            {{-- </div> --}}

            <div class="row govco-links-container">
                <div class="govco-link-container mt-2">
                    <a class="govco-link-modal govco-link-modal-bold" href="https://www.epccajica.gov.co/politicas/">Políticas</a>
                    <a class="govco-link-modal govco-link-modal-bold" href="https://www.epccajica.gov.co/mapa-sitio-epc-2022/">Mapa del sitio</a>
                </div>
                <div class="govco-link-container mt-2">
                    <a class="govco-link-modal govco-link-modal-bold" href="https://www.epccajica.gov.co/wp-content/uploads/2021/10/Terminos_condiciones_portal_EPC_II.pdf">Términos y condiciones</a> <br>
                </div>
            </div>
        </div>
        </div>

        <div class="govco-footer-logo">
        <div class="govco-logo-container">
            {{-- Logos Gov.co y Marca País (usarán CSS para mostrarse) --}}
            <span class="govco-co"></span>
            <span class="govco-separator"></span>
            <span class="govco-logo"></span>
        </div>
    </div>
</div>