<div class="contenedor crear">
    <?php
        include_once __DIR__ . '/../templates/nombreSitio.php';
    ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Crea tu cuenta en UpTask</p>
    <?php
        include_once __DIR__ . '/../templates/alertas.php';
    ?>
        <form class="formulario" action="/crear" method="POST">
            <div class="campo">
                    <label for="nombre">Nombre</label>
                    <input
                        type="text"
                        name="nombre"
                        id="nombre"
                        placeholder="Coloca tu nombre"
                        value="<?php echo $usuario->nombre ?>"
                    >
                </div>

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

                <div class="campo">
                    <label for="password2">Repetir contraseña</label>
                    <input
                        type="password"
                        name="password2"
                        id="password2"
                        placeholder="Repite tu Password"
                    >
                </div>

                <input class="boton" type="submit" value="Crear cuenta">
        </form>
        <div class="acciones">
            <a href="/">¿Ya tienes cuenta? Iniciar Sesión</a>
            <a href="/olvide">¿Olvidaste tu password?</a>
        </div>
    </div> <!-- Contenedor SM -->
</div> <!-- Contenedor -->