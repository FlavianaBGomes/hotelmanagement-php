<?php
  require_once("cabecalho.php");

  echo "<h2> Usuário: ".$_SESSION['usuario']." </h2>";
?>

<div class="row mt-4">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Quartos</h5>
                <p class="card-text">Gerencie os quartos do hotel</p>
                <a href='quartos.php' class="btn btn-primary" style="cursor: pointer;">Acessar</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Hóspedes</h5>
                <p class="card-text">Cadastre e gerencie hóspedes</p>
                <a href="hospedes.php" class="btn btn-primary">Acessar</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Reservas</h5>
                <p class="card-text">Controle as reservas do hotel</p>
                <a href="reservas.php" class="btn btn-primary">Acessar</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Estadias</h5>
                <p class="card-text">Registre check-ins e check-outs</p>
                <a href="estadias.php" class="btn btn-primary">Acessar</a>
            </div>
        </div>
    </div>
</div>

<?php
  require_once("rodape.php");
?>
