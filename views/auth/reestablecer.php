<div class="contenedor reestablecer">
    <?php @include_once __DIR__ . '/../templates/nombre-sitio.php'?>
        
    <div class="contenedor-sm">
         <p class="descripcion-pagina">Reestablece tu Passwrod</p>

        <?php @include_once __DIR__ . '/../templates/alertas.php'?>

        <?php if($mostrar){ ?>    
        <form class="formulario" method="POST">
            <div class="campo">    
                <label for="password">Password:</label>
                <input type="password"
                        id="password"
                        name="password"
                        placeholder="Pon tu Password"     
                />
            </div>
           
            
            <input type="submit" class="boton" value="Confirmar Nuevo Password">
        
        </form>

        <?php } ?>
        <div class="acciones">
            <a href="/">Iniciar Sesion</a>
            <a href="/crear">Crear Cuenta</a>


        </div>


    </div>
                    
</div>    