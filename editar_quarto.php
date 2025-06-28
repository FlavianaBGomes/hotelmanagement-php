<?php

    require_once("cabecalho.php");

    function consultaQuarto($id){
        require("config/conexao.php");
        try{
            $sql = "SELECT * FROM quartos WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            $quarto = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$quarto){
                die("Erro ao consultar o registro!");
            } else{
                return $quarto;
            }
        } catch(Exception $e){
            die("Erro ao consultar quarto: " . $e->getMessage());
        }
    }

    function alterarQuarto($numero, $tipo, $capacidade, $preco_noite, $id){
        require("config/conexao.php");
        try{
            $sql = "UPDATE quartos SET numero = ?, tipo = ?, capacidade = ?, preco_noite = ? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$numero, $tipo, $capacidade, $preco_noite, $id])){
                header('location: quartos.php?edicao=true');
            } else {
                header('location: quartos.php?edicao=false');
            }
        } catch (Exception $e){
            die("Erro ao alterar o quarto: ".$e->getMessage());
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST"){
        $numero = $_POST['numero'];
        $tipo = $_POST['tipo'];
        $capacidade = $_POST['capacidade'];
        $preco_noite = $_POST['preco_noite'];
        $id = $_POST['id'];
        alterarQuarto($numero, $tipo, $capacidade, $preco_noite, $id);
    } else {
        $quarto = consultaQuarto($_GET['id']);
    }

?>

<h2>Alterar Quarto</h2>

<form method="post">

    <input type="hidden" name="id" value="<?= $quarto['id'] ?>" >
                        
    <div class="mb-3">
        <label for="numero" class="form-label">Número do Quarto</label>
        <input value="<?= $quarto['numero'] ?>" type="text" id="numero" name="numero" class="form-control" required="">
    </div>

    <div class="mb-3">
        <label for="tipo" class="form-label">Tipo do Quarto</label>
        <select id="tipo" name="tipo" class="form-control" required="">
            <option value="">Selecione o tipo</option>
            <option value="Standard" <?= ($quarto['tipo'] == 'Standard') ? 'selected' : '' ?>>Standard</option>
            <option value="Deluxe" <?= ($quarto['tipo'] == 'Deluxe') ? 'selected' : '' ?>>Deluxe</option>
            <option value="Suite" <?= ($quarto['tipo'] == 'Suite') ? 'selected' : '' ?>>Suíte</option>
            <option value="Presidential" <?= ($quarto['tipo'] == 'Presidential') ? 'selected' : '' ?>>Presidencial</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="capacidade" class="form-label">Capacidade</label>
        <input value="<?= $quarto['capacidade'] ?>" type="number" id="capacidade" name="capacidade" class="form-control" min="1" required="">
    </div>

    <div class="mb-3">
        <label for="preco_noite" class="form-label">Preço por Noite (R$)</label>
        <input value="<?= $quarto['preco_noite'] ?>" type="number" id="preco_noite" name="preco_noite" class="form-control" step="0.01" min="0" required="">
    </div>

    <button type="submit" class="btn btn-primary">Enviar</button>
    <a href="quartos.php" class="btn btn-secondary">Cancelar</a>
</form>


<?php 
    require_once("rodape.php");

?>

