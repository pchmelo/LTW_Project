/*const search = document.querySelector('#searchInput');
const sectionInicial = document.querySelector('#productsGrid');
const htmlInicial = sectionInicial.innerHTML;

const url = new URLSearchParams(window.location.search);
const query = url.get('search');

const productlis = document.querySelectorAll('#productli');
var dados;

if (search) {
  console.log('Search input found');
  search.addEventListener('input', async function() {
    activateFilters();

    console.log('Search input changed, value:', this.value);
    const response = await fetch('../api/api_products.php?search=' + this.value)
    console.log('Response:', response); 
    const data = await response.json()
    console.log('Data: ', data); 
    dados = data;

    if(this.value.length < 3){
      for (const productli of productlis) {
        if(productli.classList.contains('escolhido')){
            productli.style.display = 'block';
        }
        else{
          productli.style.display = 'none';
        }
      }
    return;
    }

    for (const productli of productlis) {
      var produto = productli.querySelector('h3 a').textContent;
      if (!data.products.some(product => product.name === produto)) {
        productli.style.display = 'none';
        productli.classList.add('despesquisado');
      } else {
        if (productli.classList.contains('escolhido')){
          productli.classList.remove('despesquisado');
          productli.style.display = 'block';
        }
      }
    }
  })
} else {
  console.log('Search input not found'); 
}

if (query) {
  search.value = query;
  search.dispatchEvent(new Event('input'));
  url.delete('search');
  history.replaceState({}, '', '?' + url.toString());
}*/