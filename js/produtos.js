document.addEventListener('DOMContentLoaded', function () {
    const btnAdicionar = document.getElementById('btnAdicionar');
    const popup = document.getElementById('produtoPopup');
    const closePopup = document.getElementById('closePopup');
    const produtoForm = document.getElementById('produtoForm');
    const productsList = document.getElementById('productsList');

    // Exibir o pop-up para adicionar um produto
    btnAdicionar.addEventListener('click', () => {
        popup.style.display = 'block';
        document.getElementById('popupTitle').innerText = 'Adicionar Produto';
        produtoForm.reset();
        document.getElementById('produtoId').value = '';
    });

    // Fechar o pop-up
    closePopup.addEventListener('click', () => {
        popup.style.display = 'none';
    });

    // Adicionar/Alterar produto
    produtoForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const id = document.getElementById('produtoId').value;
        const codigo = document.getElementById('codigo').value;
        const nome = document.getElementById('nome').value;
        const preco = parseFloat(document.getElementById('preco').value);
        const tipo = document.getElementById('tipo').value;
        const marca = document.getElementById('marca').value;

        const produtoData = { id, codigo, nome, preco, tipo, marca };

        fetch('produtos.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(produtoData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Atualiza a lista de produtos
                loadProducts();
                popup.style.display = 'none';
            } else {
                alert(data.error);
            }
        });
    });

    // Carregar produtos do servidor
    function loadProducts() {
        fetch('produtos.php')
            .then(response => response.json())
            .then(data => {
                productsList.innerHTML = '';
                data.forEach(produto => {
                    const productItem = document.createElement('div');
                    productItem.className = 'product-item';
                    productItem.dataset.id = produto.id;
                    productItem.innerHTML = `
                        <p><strong>Código:</strong> ${produto.codigo}</p>
                        <p><strong>Nome:</strong> ${produto.nome}</p>
                        <p><strong>Preço:</strong> R$ ${parseFloat(produto.preco).toFixed(2).replace('.', ',')}</p>
                        <p><strong>Tipo:</strong> ${produto.tipo}</p>
                        <p><strong>Marca:</strong> ${produto.marca}</p>
                        <button class="btn btn-edit" data-id="${produto.id}">Alterar</button>
                        <button class="btn btn-delete" data-id="${produto.id}">Excluir</button>
                        <hr>
                    `;
                    productsList.appendChild(productItem);
                });
            });
    }

    // Evento para alterar e excluir produtos
    productsList.addEventListener('click', (e) => {
        if (e.target.classList.contains('btn-edit')) {
            const productId = e.target.dataset.id;
            // Lógica para carregar os dados do produto no pop-up para edição
            fetch(`produtos.php?id=${productId}`)
                .then(response => response.json())
                .then(produto => {
                    document.getElementById('produtoId').value = produto.id;
                    document.getElementById('codigo').value = produto.codigo;
                    document.getElementById('nome').value = produto.nome;
                    document.getElementById('preco').value = produto.preco;
                    document.getElementById('tipo').value = produto.tipo;
                    document.getElementById('marca').value = produto.marca;
                    popup.style.display = 'block';
                    document.getElementById('popupTitle').innerText = 'Alterar Produto';
                });
        } else if (e.target.classList.contains('btn-delete')) {
            const productId = e.target.dataset.id;
            if (confirm('Tem certeza que deseja excluir este produto?')) {
                fetch(`produtos.php`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: productId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadProducts();
                    } else {
                        alert(data.error);
                    }
                });
            }
        }
    });

    // Carrega produtos ao iniciar a página
    loadProducts();
});
