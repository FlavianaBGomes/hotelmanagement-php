<?php

    require_once("cabecalho.php");

    function consultaHospede($id){
        require("config/conexao.php");
        try{
            $sql = "SELECT * FROM hospedes WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            $hospede = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$hospede){
                die("Erro ao consultar o registro!");
            } else{
                return $hospede;
            }
        } catch(Exception $e){
            die("Erro ao consultar hóspede: " . $e->getMessage());
        }
    }

    function alterarHospede($nome, $sobrenome, $documento, $email, $telefone, $id){
        require("config/conexao.php");
        try{
            $sql = "UPDATE hospedes SET nome = ?, sobrenome = ?, documento = ?, email = ?, telefone = ? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$nome, $sobrenome, $documento, $email, $telefone, $id])){
                header('location: hospedes.php?edicao=true');
            } else {
                header('location: hospedes.php?edicao=false');
            }
        } catch (Exception $e){
            die("Erro ao alterar o hóspede: ".$e->getMessage());
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST"){
        $nome = $_POST['nome'];
        $sobrenome = $_POST['sobrenome'];
        $documento = $_POST['documento'];
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];
        $id = $_POST['id'];
        alterarHospede($nome, $sobrenome, $documento, $email, $telefone, $id);
    } else {
        $hospede = consultaHospede($_GET['id']);
    }

?>

<h2>Alterar Hóspede</h2>

<form method="post">

    <input type="hidden" name="id" value="<?= $hospede['id'] ?>" >
                        
    <div class="mb-3">
        <label for="nome" class="form-label">Nome</label>
        <input value="<?= $hospede['nome'] ?>" type="text" id="nome" name="nome" class="form-control" required="">
    </div>

    <div class="mb-3">
        <label for="sobrenome" class="form-label">Sobrenome</label>
        <input value="<?= $hospede['sobrenome'] ?>" type="text" id="sobrenome" name="sobrenome" class="form-control" required="">
    </div>

    <div class="mb-3">
        <label for="documento" class="form-label">Documento (CPF/RG)</label>
        <input value="<?= $hospede['documento'] ?>" type="text" id="documento" name="documento" class="form-control" required="">
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">E-mail</label>
        <input value="<?= $hospede['email'] ?>" type="email" id="email" name="email" class="form-control" required="">
    </div>

    <div class="mb-3">
        <label for="telefone" class="form-label">Telefone</label>
        <input value="<?= $hospede['telefone'] ?>" type="tel" id="telefone" name="telefone" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary">Enviar</button>
    <a href="hospedes.php" class="btn btn-secondary">Cancelar</a>
</form>


<?php 
    require_once("rodape.php");

?>

