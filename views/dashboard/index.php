<?php include_once __DIR__ . '/header-dashboard.php'; ?>

    <?php if(count($proyectos) === 0) { ?>
        <div class="contenedor-sm">
            <div class="no-proyecto">
                <p>No hay ningun Proyecto</p>
                <a href="/crear-proyecto">Crea un proyecto</a>
            </div>
        </div>
    <?php } else { ?>
        <ul class="listado-proyectos">
            <?php foreach($proyectos as $proyecto){ ?>
                <li class="proyecto">
                    <a href="/proyecto?id=<?php echo $proyecto->url;?>">
                        <?php echo $proyecto->proyecto;?>
                    </a>
                </li>


        <?php }?>    
        </ul>

    <?php }?>    


<?php include_once __DIR__ .'/footer-dashboard.php';?>




