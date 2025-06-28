<?php

    require_once("cabecalho.php");

    function inserirQuarto($numero, $tipo, $capacidade, $preco_noite){
        require("config/conexao.php");
        try{
            $sql = "INSERT INTO quartos (numero, tipo, capacidade, preco_noite) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$numero, $tipo, $capacidade, $preco_noite])){
                header('location: quartos.php?cadastro=true');
            } else {
                header('location: quartos.php?cadastro=false');
            }
        } catch (Exception $e){
            die("Erro ao inserir o quarto: ".$e->getMessage());
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST"){
        $numero = $_POST['numero'];
        $tipo = $_POST['tipo'];
        $capacidade = $_POST['capacidade'];
        $preco_noite = $_POST['preco_noite'];
        inserirQuarto($numero, $tipo, $capacidade, $preco_noite);
    }

?>

<h2>Novo Quarto</h2>

<form method="post">
                        
    <div class="mb-3">
        <label for="numero" class="form-label">Número do Quarto</label>
        <input type="text" id="numero" name="numero" class="form-control" required="">
    </div>

    <div class="mb-3">
        <label for="tipo" class="form-label">Tipo do Quarto</label>
        <select id="tipo" name="tipo" class="form-control" required="">
            <option value="">Selecione o tipo</option>
            <option value="Standard">Standard</option>
            <option value="Deluxe">Deluxe</option>
            <option value="Suite">Suíte</option>
            <option value="Presidential">Presidencial</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="capacidade" class="form-label">Capacidade</label>
        <input type="number" id="capacidade" name="capacidade" class="form-control" min="1" required="">
    </div>

    <div class="mb-3">
        <label for="preco_noite" class="form-label">Preço por Noite (R$)</label>
        <input type="number" id="preco_noite" name="preco_noite" class="form-control" step="0.01" min="0" required="">
    </div>

    <button type="submit" class="btn btn-primary">Enviar</button>
    <a href="quartos.php" class="btn btn-secondary">Cancelar</a>
</form>


<?php 
    require_once("rodape.php");

?>

