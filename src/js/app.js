

const menuMobileBtn = document.querySelector('#mobile-menu');
const cerrarMenuBtn= document.querySelector('#cerrar-menu');
const sidebar = document.querySelector('.sidebar');


if(menuMobileBtn){
    menuMobileBtn.addEventListener('click', function(){
        sidebar.classList.add('mostrar');
    })
}

if(cerrarMenuBtn){
    cerrarMenuBtn.addEventListener('click', function(){
        sidebar.classList.add('ocultar');
        setTimeout(() => {
            sidebar.classList.remove('mostrar')
            sidebar.classList.remove('ocultar')

        }, 1000);
    })
}


// eliminar la clase de mostrar dependiento del tamaño de la pantalla

window.addEventListener('resize', function(){
    const anchoPantalla = document.body.clientWidth;
    if(anchoPantalla >= 768 ){
        sidebar.classList.remove('mostrar');
    }    
})
