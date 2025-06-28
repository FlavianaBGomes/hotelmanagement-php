<?php

    require_once("cabecalho.php");

    function inserirHospede($nome, $sobrenome, $documento, $email, $telefone){
        require("config/conexao.php");
        try{
            $sql = "INSERT INTO hospedes (nome, sobrenome, documento, email, telefone) VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$nome, $sobrenome, $documento, $email, $telefone])){
                header('location: hospedes.php?cadastro=true');
            } else {
                header('location: hospedes.php?cadastro=false');
            }
        } catch (Exception $e){
            die("Erro ao inserir o hóspede: ".$e->getMessage());
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST"){
        $nome = $_POST['nome'];
        $sobrenome = $_POST['sobrenome'];
        $documento = $_POST['documento'];
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];
        inserirHospede($nome, $sobrenome, $documento, $email, $telefone);
    }

?>

<h2>Novo Hóspede</h2>

<form method="post">
                        
    <div class="mb-3">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" id="nome" name="nome" class="form-control" required="">
    </div>

    <div class="mb-3">
        <label for="sobrenome" class="form-label">Sobrenome</label>
        <input type="text" id="sobrenome" name="sobrenome" class="form-control" required="">
    </div>

    <div class="mb-3">
        <label for="documento" class="form-label">Documento (CPF/RG)</label>
        <input type="text" id="documento" name="documento" class="form-control" required="">
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">E-mail</label>
        <input type="email" id="email" name="email" class="form-control" required="">
    </div>

    <div class="mb-3">
        <label for="telefone" class="form-label">Telefone</label>
        <input type="tel" id="telefone" name="telefone" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary">Enviar</button>
    <a href="hospedes.php" class="btn btn-secondary">Cancelar</a>
</form>


<?php 
    require_once("rodape.php");

?>

