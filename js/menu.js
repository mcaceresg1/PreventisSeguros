// Menú compartido para todas las páginas
function loadMenu() {
    const menuHTML = `
    <!-- Navigation -->
    <nav id="mainNav" class="navbar navbar-default navbar-custom navbar-fixed-top">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span> Menu <i class="fa fa-bars"></i>
                </button>
                <div class="logo">
                    
                </div>
               
                <a href="index.html"><img class="cel" src="img/logos/logo.png" alt="RPV Seguros - Corredor de Seguros"></a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="menu nav navbar-nav navbar-right">
                   
                    <li class="dropdown">
                        <a class="dropbtn page-scroll" href="#services">Seguro Personas</a>
                        <div class="dropdown-content">
                            <a href="salud.html">Salud</a>
                            <a href="auto.html">Auto</a>
                            <a href="hogar.html">hogar</a>
                            <a href="vida.html">Vida</a>
                            <a href="educacion.html">Educación</a>
                            <a href="soatauto.html">Soat Auto</a>
                            <a href="soatmoto.html">Soat moto</a>
                        </div>
                    </li>
                    <li class="dropdown">
                        <a class="dropbtn page-scroll" href="#services">Seguro Empresas</a>
                        <div class="dropdown-content">
                            <a href="multiriesgo.html">Multiriesgo</a>
                            <a href="vehiculo.html">Vehículos</a>
                            <a href="transporte.html">Transporte</a>
                            <a href="trec.html">TREC</a>
                            <a href="sctr.html">SCTR</a>
                            <a href="vidaley.html">Vida Ley</a>
                            <a href="emplados.html">Trabajadores</a>
                        </div>
                    </li>
                    
                    <li>
                        <a class="page-scroll" href="nosotros.html">Nosotros</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#contact">Contacto</a>
                    </li>
                </ul>
                               
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>
    `;
    
    // Insertar el menú al inicio del body
    document.body.insertAdjacentHTML('afterbegin', menuHTML);
}

// Cargar el menú cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', loadMenu);
} else {
    loadMenu();
}

