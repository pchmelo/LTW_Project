var element = document.querySelector('h1');
var element2 = document.getElementById('searchInput');
var element3 = document.getElementById('searchImg');
element2.classList.add('active');
element3.style.display = 'block';
element2.style.display = 'block';
element.classList.add('scrolled');


element2.addEventListener('keydown', function(event){
    if (event.key === 'Enter') {
    var searchValue = element2.value;
    window.location.href = `../pages/search.php?search=${searchValue}`;
    }
});

var inputFooter = document.querySelector('#footer-searchbar');
inputFooter.addEventListener('keydown', function(event){
    if (event.key === 'Enter') {
        event.preventDefault();
        var searchValue = inputFooter.value;
        window.location.href = `../pages/search.php?search=${searchValue}`;
    }
});

var formFooter = document.querySelector('#footer-search-form');
formFooter.addEventListener('submit', function(event){
    event.preventDefault();
    var searchValue = document.querySelector('#footer-searchbar').value;
    window.location.href = `../pages/search.php?search=${searchValue}`;
});

var searchImgContainer = document.querySelector('.searchImgContainer');
searchImgContainer.addEventListener('click', function(event){
    event.preventDefault();
    var searchValue = document.querySelector('#searchInput').value;
    window.location.href = `../pages/search.php?search=${searchValue}`;
});