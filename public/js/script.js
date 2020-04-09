// Funções da página de produto

function updateProductImage(image){
    viewImage = document.getElementsByTagName('img')[0];
    viewImage.src = image.src;
}


function addToCart(){
    quantidade = document.querySelector('#counter input').value;
    window.location.href = window.location.href + '/quantidade/' + quantidade;
}