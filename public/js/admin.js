function $(htmlSelector){
	return document.querySelector(htmlSelector);
}
function $all(htmlSelector){
	return document.querySelectorAll(htmlSelector);
}

// Style Section

class Page{
  // name deve ser o mesmo de data-name da pagina: dash-admin.php
  backgroundHoverMenu = 'orange';
	constructor(name,element, pedidoId='null'){
    this.element = element;
    this.pedidoId = pedidoId;
		this.name = name;
    this.clearBackground();
    element.style.background=this.backgroundHoverMenu;
    this.showPage(this);
	}

  static delete(){
    throw new Error("Método não implementado " + this.constructor.name);
  }

  static create(){
    throw new Error("Método não implementado " + this.constructor.name);
  }

  static edit(){
    throw new Error("Método não implementado " + this.constructor.name);
  }
  
  // Style Functions
  clearBackground(){
    document.querySelectorAll('.menu li a').forEach(function(item){
			item.style.background='none';
		})
  }

  showPage(pageClass){
    this.hiddenAllPages();
    document.querySelector('.paginas div.pagina[data-name="' + pageClass.name + '"]').style.display = 'block';
    pageClass.get();
  }

  hiddenAllPages(){
    	document.querySelectorAll('.paginas div.pagina').forEach(function(item){
			item.style.display = 'none';
		})
  }// Api Functions
	get(){
		alert("É necessário implementar a classe " + this.constructor.name);
		throw new Error('É necessário implementar a classe ' + this.constructor.name);
	}


}


class Categorias extends Page{
  static editButton;
	get(){
	var htmlNode = $('.categorias table tbody');
	
	fetch('api?categorias').then(function(response){
		response.json()
			.then(function(result){
				var fetchedHtml = '';
				result.forEach(function(item){
					fetchedHtml += `
						<tr> 
							<td>${item.id}</td>
							<td>${item.nome}</td>
							<td>
							<button class="button button__primary" onclick="Categorias.edit(${item.id}, this)">editar</button>
							<button class="button button__danger" onclick="Categorias.delete(${item.id}, this)">excluir</button>	
							</td>
						</tr>`;
				})

				htmlNode.innerHTML = fetchedHtml;
			})	
		})
	}

  static delete(id, element){
  var user_choice=confirm("Deseja mesmo apagar essa categoria?");
  if ( user_choice ){
    fetch('api?delete-categorie='+ id,{method:"POST"}).then(function(response){
        console.log(response.status);
        if ( response.status == 200 ){
          if(user_choice){
            element.parentElement.parentElement.innerHTML=""
            response.text().then(function(result){
              console.log(result);
            });
            alert("Categoria excluída com sucesso");
          }
        }else{
          alert('Houve um erro ao processar seu pedido');
        }
      });
    }
  }

  static create(){
    var form = new FormData(document.querySelector('#criar-categoria'));
    var tableRows = document.querySelectorAll('.categorias.pagina table tbody tr');
    var tableRowsCount = tableRows.length;

    fetch('api', {method:"POST",body:form}).then(function(response){
      response.text().then(function(result){
          console.log(result)
          var parsedResponse = JSON.parse(result);
          if ( response.status === 200 ) {
            for (var i = 0; i < tableRowsCount; i++)
            {
              if( i === tableRowsCount - 1 ){
                document.querySelector('.categorias.pagina table').innerHTML += `
                  <tr>
                    <td>${parsedResponse.id}</td>
                    <td>${parsedResponse.nome}</td>
                    <td>
                      <button class="button button__primary" onclick="Categorias.edit(${parsedResponse.id})">editar</button>
                      <button class="button button__danger" onclick="Categorias.delete(${parsedResponse.id}, this)">excluir</button>	
                    </td>
                  </tr>
                `
              }
            }
          }
        });
      });
    return false;
  }

  static edit(id,element){
    this.editButton = element;
    var categoria = element.parentElement.parentElement.children[1].innerHTML;
    //var form = document.querySelector('#editar-categoria');
    var inputCategoriaId = document.querySelector('#editar-categoria input[name="categoria-id"]');
    inputCategoriaId.value = id;
    var inputCategoriaNome = document.querySelector('#editar-categoria input[name="editar-categoria-nome"]');
    inputCategoriaNome.value = categoria;
    console.log(id, categoria);
  }

  static update(element){
    var categoria = this.editButton.parentElement.parentElement.children[1];
    var id = this.editButton.parentElement.parentElement.children[0];
    var form = new FormData(element);
    fetch('api', { method:"POST", body:form}).then(function(response){
      console.log(response.status);
      response.json().then(function(result){
        id.innerText = result.id;
        categoria.innerText = result.categoria;
        console.log(result);
      });
    });

    return false;
    
  }


  //static edit(element, categoria){
  //  var form = element;
  //  fetch('api', {method: "POST", body:form}).then(response=>{
  //    console.log(response);
  //  });
  //}
}

class Produtos extends Page{
  static editButton;
	get(){
	var htmlNode = $('.produtos table tbody');
	
	fetch('api?produtos').then(function(response){
		response.json()
			.then(function(result){
				var fetchedHtml = '';
				result.forEach(function(item){
					fetchedHtml += `
						<tr> 
							<td>${item.id}</td>
							<td>${item.nome}</td>
							<td>${item.categoria}</td>
							<td>R$ ${item.preco.replace('.',',')}</td>
							<td>${item.estoque}</td>
							<td>
							<button class="button button__primary"onclick="Produtos.edit(${item.id}, ${item.idCategoria}, this)">editar</button>
							<button class="button button__danger"onclick="Produtos.delete(${item.id}, this)">excluir</button>	
							</td>
						</tr>`;
				})

				htmlNode.innerHTML = fetchedHtml;
			})	
		})
	}
  static delete(id, element){
    var user_choice=confirm("Deseja mesmo excluir este produto? ");
    if ( user_choice ){
      fetch('api?delete-product=' + id, {method:"POST"}).then(function(response){
        if(response.status == 200){
          if(user_choice){
            response.text().then(function(result){
              console.log(result);
            })
            // remove o produto da lista quando o usuário
            // confirma a exclusão dele
            element.parentElement.parentElement.innerHTML="";
            alert('Produto excluído com sucesso');
          }
        }else{
          alert('Houve um erro ao deletar o produto');
        }
      })
    }
  }
  
  static edit(produtoId, categoriaId, button){
    this.editButton = button;
    var produto = button.parentElement.parentElement.children[1].innerHTML;
    var preco = parseFloat(button.parentElement.parentElement.children[3].innerHTML.replace('R$ ', '').replace(',','.'));
    var quantidade = button.parentElement.parentElement.children[4].innerHTML;
    //console.log(produto, categoria, preco, quantidade);
    document.querySelector('#editar-produto input[name="editar-produto-nome"]').value=produto;
    document.querySelector('#editar-produto input[name="editar-produto-preco"]').value=preco;
    document.querySelector('#editar-produto input[name="editar-produto-quantidade"]').value=quantidade;
    document.querySelector('#editar-produto input[name="product-id"]').value=produtoId;
  }

  static update(){
    var form = new FormData(document.querySelector('#editar-produto'));
    var produto = this.editButton.parentElement.parentElement.children[1];
    var categoria = this.editButton.parentElement.parentElement.children[2];
    var preco = this.editButton.parentElement.parentElement.children[3];
    var quantidade = this.editButton.parentElement.parentElement.children[4];
    fetch('api', {method:"POST", body:form}).then(function(response){
      response.json().then(function(result){
        produto.innerHTML = result.produto;
        categoria.innerHTML = result.categoria;
        preco.innerHTML = result.preco;
        quantidade.innerHTML = result.estoque;
      });
    });
    return false;
  }

  static create(){
    var form = new FormData(document.querySelector('#criar-produto'));
    var table = document.querySelector('.produtos.pagina table');
    fetch('api', {method:'POST', body:form}).then(response=>{
      response.json().then(result=>{
        if (response.status === 200){
          table.innerHTML += `
            <tr>
              <td>${result[0].id}</td>
              <td>${result[0].nome}</td>
              <td>${result[0].categoria}</td>
              <td>${result[0].preco}</td>
              <td>${result[0].quantidade}</td>
              <td>
                <button class="button button__primary" onclick="Produtos.edit(${result[0].id}, ${result[0].idCategoria}, this)">editar</button>
							  <button class="button button__danger" onclick="Produtos.delete(${result[0].id}, this)">excluir</button>	
              </td>
            </tr>
          `;
        }
        console.log(result);
      });
    });
    return false;
  }

  static updateSelect(element){
    //var select = document.querySelector('#criar-produto select');
    var select = element;
    //pega as categorias e popula o select
    fetch('api?categorias').then(response=>{
      response.json().then(result=>{
        result.forEach(item=>{
          select.insertAdjacentHTML('beforeend', '<option value="'+ item.id +'">'+ item.nome +'</option>');
        });
      });
    });
  }


}

class Administradores extends Page{
  static editButton;
	get(){
	var htmlNode = $('.administradores table tbody');
	
	fetch('api?administradores').then(function(response){
		response.json()
			.then(function(result){
				var fetchedHtml = '';
				result.forEach(function(item){
					fetchedHtml += `
						<tr> 
							<td>${item.id}</td>
							<td>${item.nome}</td>
							<td>${item.email}</td>
							<td>
							<button class="button button__primary"onclick="Administradores.edit(${item.id}, this)">editar</button>
							<button class="button button__danger"onclick="Administradores.delete(${item.id}, this)">excluir</button>	
							</td>
						</tr>`;
				})

				htmlNode.innerHTML = fetchedHtml;
			})	
		})
	}

  static delete(id,element){
    var user_choice=confirm("Deseja realmente apagar este administrador? ");
    if (user_choice) {
    fetch('api?delete-admin=' + id, {method:"POST"})
      .then(function(response){
        if(response.status == 200){
          if( user_choice ){
            response.text()
              .then(function(result){
                console.log(result)
              })
            element.parentElement.parentElement.innerHTML="";
            alert("Administrador excluído com sucesso");
          }
        }else{
          alert("Houve um erro ao deletar o administrador");
        }
      });
    }
  }

  static create(){
    var form = new FormData(document.querySelector('#criar-administrador'));
    var table = document.querySelector('.administradores.pagina table tbody');

    fetch('api', {method:"POST", body:form}).then(response=>{
      response.json().then(result=>{
        if ( response.status === 200){
          table.innerHTML += `
            <tr>
              <td>${result.id}</td>
              <td>${result.nome}</td>
              <td>${result.email}</td>
              <td>
                <button class="button button__primary" onclick="Produtos.edit(${result.id}, this)">editar</button>
							  <button class="button button__danger" onclick="Produtos.delete(${result.id}, this)">excluir</button>	
              </td>
            </tr>
          `;
        }
        console.log(result);
      });
    });
    return false;
  }

  static edit(id,element){
    var rowNode = element.parentElement.parentElement.children;
    this.editButton = rowNode;
    var nome = rowNode[1].innerText;
    var email = rowNode[2].innerText;
    document.querySelector('#editar-admin input[name="nome"]').value=nome;
    document.querySelector('#editar-admin input[name="email"]').value=email;
    document.querySelector('#editar-admin input[name="id"]').value=id;
  }

  static update(form){
    form = new FormData(form);
    fetch('api', {method:"POST", body:form}).then(function(response){
      response.json().then(function(result){
        console.log(result);
        if(response.status == 200){
          Administradores.editButton[1].innerText = result.nome;
          Administradores.editButton[2].innerText = result.email;
        }
      });
    });
    return false;
  }

}

class Pedidos extends Page{
	get(){
	var htmlNode = $('.pedidos table tbody');
	
	fetch('api?pedidos').then(function(response){
		response.json()
			.then(function(result){
				var fetchedHtml = '';
				result.forEach(function(item){
					fetchedHtml += `
						<tr> 
							<td>${item.id}</td>
							<td>${ ( item.status_pedido == 0 ) ? "Em Andamento" : "Finalizado"   }</td>
							<td> <a onclick ="new DetalhePedido('detalhes',this, ${item.id});" href="javascript:void(0)">mais detalhes</a></td>
						</tr>`;
				})

				htmlNode.innerHTML = fetchedHtml;
			})	
		})
	}
}

class DetalhePedido extends Page{
  getConstrutorValues(){
    console.log(this.name, this.element, this.pedidoId);
  }

	get(){
	var htmlNode = $('.detalhes-pedido table tbody');
	var htmlNode2 = $('.display-pedido-info');
	var title = $('.detalhes-pedido h1');
	var pedidoId = this.pedidoId;
	fetch('api?pedido=' + this.pedidoId).then(function(response){
		response.json()
			.then(function(result){
				var fetchedHtml = '';
				var fetchedHtml2 = '';
				var total = 0;
				var userInfo = '';
				result.forEach(function(item){
					userInfo = item;
					total += (item.preco * item.quantidade);
					fetchedHtml += `
						<tr> 
							<td>${item.produto}</td>
							<td>R$ ${item.preco.replace('.',',')}</td>
							<td>${item.quantidade}</td>
							<td>R$ ${ (item.preco * item.quantidade).toFixed(2).replace('.',',') }</td>
						</tr>`;
				})
				fetchedHtml2 += `
					<p>Nome: ${userInfo.nome}</p>
					<p>Email: ${userInfo.email}</p>
					<p>Estado: ${userInfo.estado}</p>
					<p>Cidade: ${userInfo.cidade}</p>
					<p>Bairro: ${userInfo.bairro}</p>
					<p>Endereco: ${userInfo.endereco}</p>
					<p>CEP: ${userInfo.cep}</p>
					<p>Pagamento: ${userInfo.meio_pagamento}</p>
				`;
				if( userInfo.idPagamento != "default" ){
					var mercadoPagoUrl = 'https://api.mercadopago.com/v1/payments';
					var accessToken = '?access_token=TEST-8864676676772087-041722-7ef8cc5db28f3f3fce77e4b05395c34e-194214343';
					fetchedHtml2 += `<a href="${mercadoPagoUrl + "/" + userInfo.idPagamento + accessToken}">Detalhes do pagamento</a>`;
				}



				htmlNode.innerHTML = fetchedHtml;
				htmlNode.innerHTML += `
				<tr>
					<td colspan="3"></td>
					<td><b>Total:</b> R$ ${total.toFixed(2).replace('.',',')}</td>
				</tr>`
				htmlNode2.innerHTML = fetchedHtml2;
				title.innerHTML = title.innerHTML.replace(/[0-9]+/,pedidoId);
			})	
		})
	}

}

