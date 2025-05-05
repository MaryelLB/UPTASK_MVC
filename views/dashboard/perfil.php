<?php include_once __DIR__ . "/header-dashboard.php"; ?>
    <div class="contenedor-sm">
        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>
        <a href="/cambiar-password" class="enlace">Cambiar Password</a>
        <form method="POST" action="/perfil" class="formulario">
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input
                    type="text"
                    name="nombre"
                    id="nombre"
                    placeholder="Coloca tu nombre"
                    value="<?php echo s($usuario->nombre)?>"
                    >
            </div>

            <div class="campo">
                <label for="email">Email</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    placeholder="Coloca tu email"
                    value="<?php echo s($usuario->email)?>"
                    >
            </div>

            <input type="submit" value="Guardar Cambios">
        </form>
    
    </div>
<?php include_once __DIR__ . "/footer-dashboard.php"; ?>