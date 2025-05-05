<div class="contenedor olvide">
    <?php
        include_once __DIR__ . '/../templates/nombreSitio.php';
    ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Recupera tu acceso en UpTask</p>
    <?php
        include_once __DIR__ . '/../templates/alertas.php';
    ?>
        
        <form class="formulario" action="/olvide" method="POST">
                <div class="campo">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        placeholder="Tu email"
                    >
                </div>
                <input class="boton" type="submit" value="Enviar instrucciones">
        </form>
        <div class="acciones">
            <a href="/">¿Ya tienes cuenta? Iniciar Sesión</a>
            <a href="/crear">¿Aún no tienes una cuenta? Obtener Una</a>
        </div>
    </div> <!-- Contenedor SM -->
</div> <!-- Contenedor -->