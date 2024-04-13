<div class="contenedor login">
    <?php @include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Inicia Sesion</p>
    <?php @include_once __DIR__ . '/../templates/alertas.php'; ?>


        <form action="/" class="formulario" method="POST" novalidate>
            <div class="campo">    
                <label for="email">Email:</label>
                    <input type="email"
                            id="email"
                            name="email"
                            placeholder="Pon tu Email"     
                    />
            </div>
            <div class="campo">    
                <label for="password">Password:</label>
                    <input type="password"
                            id="password"
                            name="password"
                            placeholder="Pon tu Password"     
                    />
            </div>

            <input type="submit" class="boton" value="Iniciar Sesión">
        </form>

        <div class="acciones">
            <a href="/crear">Si no tienes cuenta. Crear cuenta</a>
            <a href="/olvide">Olvide mi Password</a>

        </div>


    </div> <!-- final del contenedor-sm -->




</div>

