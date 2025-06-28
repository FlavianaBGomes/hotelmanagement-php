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
            die("Erro ao consultar reservas: ". $e->getMessage());
        }
    }

    function retornaQuartos(){
        require("config/conexao.php");
        try{
            $sql = "SELECT * FROM quartos ORDER BY numero";
            $stmt = $pdo->query($sql);
            return $stmt->fetchAll();
        } catch (Exception $e){
            die("Erro ao consultar quartos: ". $e->getMessage());
        }
    }

    function consultaEstadia($id){
        require("config/conexao.php");
        try{
            $sql = "SELECT e.*, r.data_inicio, r.data_fim, h.nome, h.sobrenome, q.numero as quarto_numero
                    FROM estadias e
                    LEFT JOIN reservas r ON e.reserva_id = r.id
                    LEFT JOIN hospedes h ON r.hospede_id = h.id
                    LEFT JOIN quartos q ON e.quarto_id = q.id
                    WHERE e.id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            $estadia = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$estadia){
                die("Erro ao consultar o registro!");
            } else{
                return $estadia;
            }
        } catch(Exception $e){
            die("Erro ao consultar estadia: " . $e->getMessage());
        }
    }

    function alterarEstadia($reserva_id, $quarto_id, $data_checkin, $data_checkout, $id){
        require("config/conexao.php");
        try{
            $sql = "UPDATE estadias SET reserva_id = ?, quarto_id = ?, data_checkin = ?, data_checkout = ? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$reserva_id, $quarto_id, $data_checkin, $data_checkout, $id])){
                header('location: estadias.php?edicao=true');
            } else {
                header('location: estadias.php?edicao=false');
            }
        } catch (Exception $e){
            die("Erro ao alterar a estadia: ".$e->getMessage());
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST"){
        $reserva_id = $_POST['reserva_id'];
        $quarto_id = $_POST['quarto_id'];
        $data_checkin = $_POST['data_checkin'];
        $data_checkout = $_POST['data_checkout'] ?: null;
        $id = $_POST['id'];
        alterarEstadia($reserva_id, $quarto_id, $data_checkin, $data_checkout, $id);
    } else {
        $estadia = consultaEstadia($_GET['id']);
    }

    $reservas = retornaReservas();
    $quartos = retornaQuartos();

?>

<h2>Alterar Estadia</h2>

<form method="post">

    <input type="hidden" name="id" value="<?= $estadia['id'] ?>" >
                        
    <div class="mb-3">
        <label for="reserva_id" class="form-label">Reserva</label>
        <select id="reserva_id" name="reserva_id" class="form-control" required="">
            <option value="">Selecione uma reserva</option>
            <?php foreach($reservas as $r): ?>
                <option value="<?= $r['id'] ?>" <?= ($estadia['reserva_id'] == $r['id']) ? 'selected' : '' ?>>
                    <?= $r['nome'] . ' ' . $r['sobrenome'] . ' - ' . date('d/m/Y', strtotime($r['data_inicio'])) . ' a ' . date('d/m/Y', strtotime($r['data_fim'])) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="quarto_id" class="form-label">Quarto</label>
        <select id="quarto_id" name="quarto_id" class="form-control" required="">
            <option value="">Selecione um quarto</option>
            <?php foreach($quartos as $q): ?>
                <option value="<?= $q['id'] ?>" <?= ($estadia['quarto_id'] == $q['id']) ? 'selected' : '' ?>>
                    Quarto <?= $q['numero'] ?> - <?= $q['tipo'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="data_checkin" class="form-label">Data e Hora do Check-in</label>
        <input value="<?= date('Y-m-d\TH:i', strtotime($estadia['data_checkin'])) ?>" type="datetime-local" id="data_checkin" name="data_checkin" class="form-control" required="">
    </div>

    <div class="mb-3">
        <label for="data_checkout" class="form-label">Data e Hora do Check-out</label>
        <input value="<?= $estadia['data_checkout'] ? date('Y-m-d\TH:i', strtotime($estadia['data_checkout'])) : '' ?>" type="datetime-local" id="data_checkout" name="data_checkout" class="form-control">
        <small class="form-text text-muted">Deixe em branco se o hóspede ainda não fez check-out</small>
    </div>

    <button type="submit" class="btn btn-primary">Enviar</button>
    <a href="estadias.php" class="btn btn-secondary">Cancelar</a>
</form>


<?php 
    require_once("rodape.php");

?>

