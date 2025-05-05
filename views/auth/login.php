<div class="contenedor login">
    <?php
        include_once __DIR__ . '/../templates/nombreSitio.php';
    ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Iniciar Sesion</p>
    <?php
        include_once __DIR__ . '/../templates/alertas.php';
    ?>
        <form class="formulario" action="/" method="POST">
                <div class="campo">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        placeholder="Tu email"
                        value="<?php echo $usuario->email ?>"
                    >
                </div>

                <div class="campo">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        placeholder="Tu Password"
                    >
                </div>

                <input class="boton" type="submit" value="Iniciar Sesion">
        </form>
        <div class="acciones">
            <a href="/crear">¿Aún no tienes una cuenta? Obtener Una</a>
            <a href="/olvide">¿Olvidaste tu password?</a>
        </div>
    </div> <!-- Contenedor SM -->
</div> <!-- Contenedor -->