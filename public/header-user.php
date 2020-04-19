<header>
    <!-- Menu de navegação do site -->
    <nav id="top-menu">
        <ul id="top-menu-left">
            <li><a href="/ecommerce" title="Leva a página incial">Ecommerce</a></li>
            <li><a href="#pedidos" title="Acompanhamento do adamento dos Produtos">Pedidos</a></li>
            
        </ul>

        <ul id="top-menu-right">
            <li><a href="#">
            <?= $_SESSION['userInfo']['nome'] ?? 'Teste' ;?>
            </a></li>
            <ul>
                <li>
                    <a href="#editar" title="Permite modificar dados da sua conta como senha entre outras opções">editar</a>
                </li>
                <li><a href="logout.php" title="Encerra sua sessão">sair</a></li>
            </ul>    
        </ul>
    </nav>
</header>