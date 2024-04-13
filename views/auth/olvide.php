<div class="contenedor olvide">
    <?php @include_once __DIR__ . '/../templates/nombre-sitio.php'?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Recuperar tu Password</p>

    <?php @include_once __DIR__ . '/../templates/alertas.php'?>



        <form action="/olvide" class="formulario" method="POST">
            <div class="campo">    
                <label for="email">EMail:</label>
                    <input type="email"
                            id="email"
                            name="email"
                            placeholder="Pon tu email"
                            
                    />
            </div>

           <input type="submit" class="boton" value="Enviar Instrucciones" > 
        </form>

        <div class="acciones">
            <a href="/">Volver a Iniciar Sesion</a>
            <a href="/crear">Volver a Crear Cuenta</a>
        </div>
    </div>
</div>