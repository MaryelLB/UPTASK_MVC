<?php include_once __DIR__ . "/header-dashboard.php"; ?>
    <div class="contenedor-sm">
        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>
        <a href="/perfil" class="enlace">Volver al Perfil</a>
        <form method="POST" action="/cambiar-password" class="formulario">
            <div class="campo">
                <label for="password_actual">Password Actual</label>
                <input
                    type="password"
                    name="password_actual"
                    placeholder="Coloca tu password actual"
                    value=""
                    >
            </div>

            <div class="campo">
                <label for="password_nuevo">Password Nuevo</label>
                <input
                    type="password"
                    name="password_nuevo"
                    placeholder="Coloca tu nuevo password"
                    value=""
                    >
            </div>

            <!-- <div class="campo">
                <label for="password">Password</label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    placeholder="Coloca tu password"
                    value=""
                    >
            </div> -->

            <input type="submit" value="Guardar Cambios">
        </form>
    
    </div>
<?php include_once __DIR__ . "/footer-dashboard.php"; ?>