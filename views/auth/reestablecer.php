<div class="contenedor reestablecer">
    <?php
        include_once __DIR__ . '/../templates/nombreSitio.php';
    ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Coloca tu nuevo Password</p>

    <?php
        include_once __DIR__ . '/../templates/alertas.php';
    ?>

    <?php if($mostrar) { ?>
        
        <form class="formulario" action="/reestablecer" method="POST">
                <div class="campo">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        placeholder="Tu Password"
                    >
                </div>
                <input class="boton" type="submit" value="Guardar Password">
        </form>
        <?php } ?>
        <div class="acciones">
            <a href="/">¿Ya tienes cuenta? Iniciar Sesión</a>
            <a href="/olvide">¿Olvidaste tu password?</a>
        </div>
    </div> <!-- Contenedor SM -->
</div> <!-- Contenedor -->