window.onload = function() {
    offCanvas();
}

function offCanvas() {
    document.getElementsByClassName('navbar-toggler-right')[0].onclick = function() {
        document.getElementsByClassName('sidebar-offcanvas')[0].classList.toggle('active');
    }
}
