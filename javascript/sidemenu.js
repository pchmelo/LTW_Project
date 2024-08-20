var hamburger = document.getElementById('hamburger');
var victim = document.getElementById('container');
var header = document.getElementById('fixedHeader');
var lateralmenu = document.querySelector('.menu-lateral');
var sideoptions = document.getElementById('sideOptions');
var counter2 = 0;

function hideMenu() {
    counter2++;
    lateralmenu.classList.remove('animation');
    document.body.style.overflow = 'auto';
    header.style.position = 'fixed';
    document.removeEventListener('click', checkClickOutsideMenu);

}

function checkClickOutsideMenu(event) {
    var isClickInsideMenu = lateralmenu.contains(event.target);
    if (!isClickInsideMenu) {
        hideMenu();
    }
}

hamburger.addEventListener('click', function(event){
    console.log('click event fired');
    counter2++;
    console.log('counter:', counter2);
    if (counter2 % 2 !== 0) {
        document.body.style.overflow = 'hidden';
        lateralmenu.classList.add('animation');

        document.addEventListener('click', checkClickOutsideMenu);
    } else {
        hideMenu();
    }
    event.stopPropagation();
});