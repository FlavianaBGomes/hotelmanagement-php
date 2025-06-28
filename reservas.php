<?php
  require_once("cabecalho.php");

  function retornaReservas(){
    require("config/conexao.php");
    try{
      $sql = "SELECT r.*, h.nome, h.sobrenome 
              FROM reservas r
              LEFT JOIN hospedes h ON r.hospede_id = h.id
              ORDER BY r.data_inicio DESC";
      $stmt = $pdo->query($sql);
      return $stmt->fetchAll();
    } catch (Exception $e){
      die("Erro ao consultar as reservas: ". $e->getMessage());
    }
  }

  $reservas = retornaReservas();

?>

<h2>Reservas</h2>
<a href="nova_reserva.php" class="btn btn-success mb-3">Novo Registro</a>

<?php
    if (isset($_GET['cadastro']) && $_GET['cadastro'] == true){
      echo '<p class="text-success">Registro salvo com sucesso!</p>';
    } elseif (isset($_GET['cadastro']) && $_GET['cadastro'] == false){
      echo '<p class="text-danger">Erro ao inserir o registro!</p>';
    }
    if (isset($_GET['edicao']) && $_GET['edicao'] == true){
      echo '<p class="text-success">Registro alterado com sucesso!</p>';
    } elseif (isset($_GET['edicao']) && $_GET['edicao'] == false){
      echo '<p class="text-danger">Erro ao alterar o registro!</p>';
    }
    if (isset($_GET['exclusao']) && $_GET['exclusao'] == true){
      echo '<p class="text-success">Registro excluído com sucesso!</p>';
    } elseif (isset($_GET['exclusao']) && $_GET['exclusao'] == false){
      echo '<p class="text-danger">Erro ao excluir o registro!</p>';
    }
?>

<table class="table table-hover table-striped" id="tabela">
    <thead>
        <tr>
            <th>ID</th>
            <th>Hóspede</th>
            <th>Data Início</th>
            <th>Data Fim</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php
          foreach($reservas as $r):
        ?>
            <tr>
                <td><?= $r['id'] ?></td>
                <td><?= $r['nome'] . ' ' . $r['sobrenome'] ?></td>
                <td><?= date('d/m/Y', strtotime($r['data_inicio'])) ?></td>
                <td><?= date('d/m/Y', strtotime($r['data_fim'])) ?></td>
                <td>
                    <span class="badge bg-<?= ($r['status'] == 'confirmada') ? 'success' : (($r['status'] == 'cancelada') ? 'danger' : 'primary') ?>">
                        <?= ucfirst($r['status']) ?>
                    </span>
                </td>
                <td>
                    <a href="editar_reserva.php?id=<?= $r['id'] ?>" class="btn btn-warning">Editar</a>
                    <a href="consultar_reserva.php?id=<?= $r['id'] ?>" class="btn btn-info">Consultar</a>
                </td>
            </tr>
        <?php
          endforeach;
        ?>
    </tbody>
</table>
                
<?php
  require_once("rodape.php");
?>

