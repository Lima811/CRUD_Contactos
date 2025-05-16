
        <nav>
            <?php
				if (isset($_SESSION["tipo"])) {
    if ($_SESSION["tipo"] == "Administrador") {
        ?>
        <ul>
          <li><a href="tabusuarios.php" class="menu">CRUD USUARIOS</a></li>
          <li><a href="tabcontactosdos.php" class="menu">CRUD CONTACTOS</a></li>
          <li><a href="tabpassword.php" class="menu">CRUD CONTRASE&Ntilde;AS </a></li>
          <li><a href="logout.php" class="menu">Salir</a></li>
        </ul>
        <?php
    } else if ($_SESSION["tipo"] == "Visualizador") {
        ?>
        <ul>
          <li><a href="tabcontactos.php" class="menu">CRUD CONTACTOS</a></li>
          <li><a href="logout.php" class="menu">Salir</a></li>
        </ul>
        <?php
    }
                                }
			?>
            
        </nav>
