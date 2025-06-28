<?php
require_once '../config/database.php';
require_once '../models/Estadia.php';
require_once '../models/Reserva.php';
require_once '../models/Quarto.php';

$database = new Database();
$db = $database->getConnection();
$estadia = new Estadia($db);
$reserva = new Reserva($db);
$quarto = new Quarto($db);

$message = '';

// Processar ações
if ($_POST) {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create':
                $estadia->reserva_id = $_POST['reserva_id'];
                $estadia->quarto_id = $_POST['quarto_id'];
                $estadia->data_checkin = $_POST['data_checkin'];
                $estadia->data_checkout = $_POST['data_checkout'] ?: null;
                
                if ($estadia->create()) {
                    $message = "Estadia registrada com sucesso!";
                } else {
                    $message = "Erro ao registrar estadia.";
                }
                break;
                
            case 'update':
                $estadia->id = $_POST['id'];
                $estadia->reserva_id = $_POST['reserva_id'];
                $estadia->quarto_id = $_POST['quarto_id'];
                $estadia->data_checkin = $_POST['data_checkin'];
                $estadia->data_checkout = $_POST['data_checkout'] ?: null;
                
                if ($estadia->update()) {
                    $message = "Estadia atualizada com sucesso!";
                } else {
                    $message = "Erro ao atualizar estadia.";
                }
                break;
                
            case 'delete':
                $estadia->id = $_POST['id'];
                if ($estadia->delete()) {
                    $message = "Estadia deletada com sucesso!";
                } else {
                    $message = "Erro ao deletar estadia.";
                }
                break;
        }
    }
}

// Buscar estadia para edição
$edit_estadia = null;
if (isset($_GET['edit'])) {
    $estadia->id = $_GET['edit'];
    if ($estadia->readOne()) {
        $edit_estadia = $estadia;
    }
}

// Listar todas as estadias
$stmt = $estadia->read();

// Listar reservas para o select
$reservas_stmt = $reserva->read();

// Listar quartos para o select
$quartos_stmt = $quarto->read();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Estadias - Sistema de Gestão de Hotéis</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <h1>Sistema de Gestão de Hotéis</h1>
        <nav>
            <ul>
                <li><a href="../index.php">Início</a></li>
                <li><a href="quartos.php">Quartos</a></li>
                <li><a href="hospedes.php">Hóspedes</a></li>
                <li><a href="reservas.php">Reservas</a></li>
                <li><a href="estadias.php" class="active">Estadias</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="container">
            <h2>Gerenciar Estadias</h2>
            
            <?php if ($message): ?>
                <div class="message"><?php echo $message; ?></div>
            <?php endif; ?>

            <!-- Formulário para criar/editar estadia -->
            <div class="form-section">
                <h3><?php echo $edit_estadia ? 'Editar Estadia' : 'Nova Estadia'; ?></h3>
                <form method="POST">
                    <input type="hidden" name="action" value="<?php echo $edit_estadia ? 'update' : 'create'; ?>">
                    <?php if ($edit_estadia): ?>
                        <input type="hidden" name="id" value="<?php echo $edit_estadia->id; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="reserva_id">Reserva:</label>
                        <select id="reserva_id" name="reserva_id" required>
                            <option value="">Selecione uma reserva</option>
                            <?php while ($reserva_row = $reservas_stmt->fetch(PDO::FETCH_ASSOC)): ?>
                                <option value="<?php echo $reserva_row['id']; ?>" 
                                        <?php echo ($edit_estadia && $edit_estadia->reserva_id == $reserva_row['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($reserva_row['nome'] . ' ' . $reserva_row['sobrenome'] . ' - ' . date('d/m/Y', strtotime($reserva_row['data_inicio'])) . ' a ' . date('d/m/Y', strtotime($reserva_row['data_fim']))); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="quarto_id">Quarto:</label>
                        <select id="quarto_id" name="quarto_id" required>
                            <option value="">Selecione um quarto</option>
                            <?php while ($quarto_row = $quartos_stmt->fetch(PDO::FETCH_ASSOC)): ?>
                                <option value="<?php echo $quarto_row['id']; ?>" 
                                        <?php echo ($edit_estadia && $edit_estadia->quarto_id == $quarto_row['id']) ? 'selected' : ''; ?>>
                                    Quarto <?php echo htmlspecialchars($quarto_row['numero'] . ' - ' . $quarto_row['tipo']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="data_checkin">Data e Hora do Check-in:</label>
                        <input type="datetime-local" id="data_checkin" name="data_checkin" 
                               value="<?php echo $edit_estadia ? date('Y-m-d\TH:i', strtotime($edit_estadia->data_checkin)) : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="data_checkout">Data e Hora do Check-out:</label>
                        <input type="datetime-local" id="data_checkout" name="data_checkout" 
                               value="<?php echo ($edit_estadia && $edit_estadia->data_checkout) ? date('Y-m-d\TH:i', strtotime($edit_estadia->data_checkout)) : ''; ?>">
                        <small>Deixe em branco se o hóspede ainda não fez check-out</small>
                    </div>
                    
                    <button type="submit" class="btn"><?php echo $edit_estadia ? 'Atualizar' : 'Registrar'; ?></button>
                    <?php if ($edit_estadia): ?>
                        <a href="estadias.php" class="btn btn-secondary">Cancelar</a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Lista de estadias -->
            <div class="table-section">
                <h3>Estadias Registradas</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Hóspede</th>
                            <th>Quarto</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['nome'] . ' ' . $row['sobrenome']); ?></td>
                            <td>Quarto <?php echo htmlspecialchars($row['quarto_numero']); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($row['data_checkin'])); ?></td>
                            <td>
                                <?php if ($row['data_checkout']): ?>
                                    <?php echo date('d/m/Y H:i', strtotime($row['data_checkout'])); ?>
                                <?php else: ?>
                                    <span class="status status-ativo">Hospedado</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($row['data_checkout']): ?>
                                    <span class="status status-concluida">Finalizada</span>
                                <?php else: ?>
                                    <span class="status status-ativo">Ativa</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="estadias.php?edit=<?php echo $row['id']; ?>" class="btn btn-small">Editar</a>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja deletar esta estadia?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="btn btn-small btn-danger">Deletar</button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Sistema de Gestão de Hotéis - Flaviana Bataliotti Gomes</p>
    </footer>
</body>
</html>

