<?php
  require_once("cabecalho.php");

  function retornaEstadias(){
    require("config/conexao.php");
    try{
      $sql = "SELECT e.*, r.data_inicio, r.data_fim, h.nome, h.sobrenome, q.numero as quarto_numero
              FROM estadias e
              LEFT JOIN reservas r ON e.reserva_id = r.id
              LEFT JOIN hospedes h ON r.hospede_id = h.id
              LEFT JOIN quartos q ON e.quarto_id = q.id
              ORDER BY e.data_checkin DESC";
      $stmt = $pdo->query($sql);
      return $stmt->fetchAll();
    } catch (Exception $e){
      die("Erro ao consultar as estadias: ". $e->getMessage());
    }
  }

  $estadias = retornaEstadias();

?>

<h2>Estadias</h2>
<a href="nova_estadia.php" class="btn btn-success mb-3">Novo Registro</a>

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
            <th>Quarto</th>
            <th>Check-in</th>
            <th>Check-out</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php
          foreach($estadias as $e):
        ?>
            <tr>
                <td><?= $e['id'] ?></td>
                <td><?= $e['nome'] . ' ' . $e['sobrenome'] ?></td>
                <td>Quarto <?= $e['quarto_numero'] ?></td>
                <td><?= date('d/m/Y H:i', strtotime($e['data_checkin'])) ?></td>
                <td>
                    <?php if ($e['data_checkout']): ?>
                        <?= date('d/m/Y H:i', strtotime($e['data_checkout'])) ?>
                    <?php else: ?>
                        <span class="badge bg-warning">Hospedado</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($e['data_checkout']): ?>
                        <span class="badge bg-success">Finalizada</span>
                    <?php else: ?>
                        <span class="badge bg-primary">Ativa</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="editar_estadia.php?id=<?= $e['id'] ?>" class="btn btn-warning">Editar</a>
                    <a href="consultar_estadia.php?id=<?= $e['id'] ?>" class="btn btn-info">Consultar</a>
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

