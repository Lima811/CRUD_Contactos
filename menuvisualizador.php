<nav>
            <?php
				if (isset($_SESSION["tipo"])){
					if ($_SESSION["tipo"]=="Visualizador"){
			
					}
			?>
        <ul>
          <li><a href="tabcontactos.php" class="menu">CRUD CONTACTOS</a></li>
          
  				<li><a href="logout.php" class="menu">Salir</a></li>
        </ul>

			<?php
				} else {
			?>
			<ul>
				<li> <a href="#" class="menu">Opcion 1</a> </li>
				<li> <a href="#" class="menu">Opcion 2</a> </li>
				<li> <a href="#" class="menu">Opcion 3</a> </li>
			</ul>
			<?php
				}
			?>
        </nav>

