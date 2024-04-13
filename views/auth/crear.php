<div class="contenedor crear">

    <?php @include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Crear una cuenta en UpTask</p>

        <?php @include_once __DIR__ . '/../templates/alertas.php'; ?>

        <form action="/crear" class="formulario" method="POST">
            <div class="campo">    
                <label for="nombre">Nombre:</label>
                    <input type="text"
                            id="nombre"
                            name="nombre"
                            placeholder="Pon tu Nombre" 
                            value="<?php echo $usuario->nombre;?>"    
                    />
            </div>

       
            <div class="campo">    
                <label for="email">Email:</label>
                    <input type="email"
                            id="email"
                            name="email"
                            placeholder="Pon tu Email"
                            value="<?php echo $usuario->email;?>"      
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
            <div class="campo">    
                <label for="password2">Repite tu Password:</label>
                    <input type="password"
                            id="password2"
                            name="password2"
                            placeholder="Repite tu Password"     
                    />
            </div>

            <input type="submit" class="boton" value="Crear Cuenta">
        </form>

        <div class="acciones">
            <a href="/">Si tienes cuenta. Inicia Sesion</a>
            <a href="/olvide">Olvide mi Password</a>
            

        </div>


    </div> <!-- final del contenedor-sm -->




</div>

