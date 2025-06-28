<?php
  require_once("cabecalho.php");

  function retornaHospedes(){
    require("config/conexao.php");
    try{
      $sql = "SELECT * from hospedes ORDER BY nome, sobrenome";
      $stmt = $pdo->query($sql);
      return $stmt->fetchAll();
    } catch (Exception $e){
      die("Erro ao consultar os hóspedes: ". $e->getMessage());
    }
  }

  $hospedes = retornaHospedes();

?>

<h2>Hóspedes</h2>
<a href="novo_hospede.php" class="btn btn-success mb-3">Novo Registro</a>

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
            <th>Nome</th>
            <th>Documento</th>
            <th>Email</th>
            <th>Telefone</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php
          foreach($hospedes as $h):
        ?>
            <tr>
                <td><?= $h['id'] ?></td>
                <td><?= $h['nome'] . ' ' . $h['sobrenome'] ?></td>
                <td><?= $h['documento'] ?></td>
                <td><?= $h['email'] ?></td>
                <td><?= $h['telefone'] ?></td>
                <td>
                    <a href="editar_hospede.php?id=<?= $h['id'] ?>" class="btn btn-warning">Editar</a>
                    <a href="consultar_hospede.php?id=<?= $h['id'] ?>" class="btn btn-info">Consultar</a>
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

