<?php
  require_once("cabecalho.php");

  function retornaQuartos(){
    require("config/conexao.php");
    try{
      $sql = "SELECT * from quartos ORDER BY numero";
      $stmt = $pdo->query($sql);
      return $stmt->fetchAll();
    } catch (Exception $e){
      die("Erro ao consultar os quartos: ". $e->getMessage());
    }
  }

  $quartos = retornaQuartos();

?>

<h2>Quartos</h2>
<a href="novo_quarto.php" class="btn btn-success mb-3">Novo Registro</a>

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
            <th>Número</th>
            <th>Tipo</th>
            <th>Capacidade</th>
            <th>Preço/Noite</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php
          foreach($quartos as $q):
        ?>
            <tr>
                <td><?= $q['id'] ?></td>
                <td><?= $q['numero'] ?></td>
                <td><?= $q['tipo'] ?></td>
                <td><?= $q['capacidade'] ?></td>
                <td>R$ <?= number_format($q['preco_noite'], 2, ',', '.') ?></td>
                <td>
                    <a href="editar_quarto.php?id=<?= $q['id'] ?>" class="btn btn-warning">Editar</a>
                    <a href="consultar_quarto.php?id=<?= $q['id'] ?>" class="btn btn-info">Consultar</a>
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

