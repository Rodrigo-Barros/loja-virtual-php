<header>
    <!-- Menu de navegação do site -->
    <nav>
        <ul class="nav-left">
            <li><a href="#">Ecommerce</a></li>
        </ul>  
        <ul class="nav-right">
            <li><a href="#">Home</a></li>
        <?php if (empty($_SESSION['userInfo'])): ?>
            <li><a href="registro">Cadastre-se</a></li>
            <li><a href="login">Login</a></li>
        <?php elseif($_SESSION['userInfo']['userType']=='user'): ?>
            <li><a href="dashboard">Painel de usuário</a></li>
        <?php elseif($_SESSION['userInfo']['userType']=='admin'): ?>
            <li><a href="dashboard-admin">Painel de Admin</a></li>
        <?php endif;?>
        </ul>
    </nav>
</header>