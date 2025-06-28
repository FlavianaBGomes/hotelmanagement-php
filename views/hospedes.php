<?php
require_once '../config/database.php';
require_once '../models/Hospede.php';

$database = new Database();
$db = $database->getConnection();
$hospede = new Hospede($db);

$message = '';

// Processar ações
if ($_POST) {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create':
                $hospede->nome = $_POST['nome'];
                $hospede->sobrenome = $_POST['sobrenome'];
                $hospede->documento = $_POST['documento'];
                $hospede->email = $_POST['email'];
                $hospede->telefone = $_POST['telefone'];
                
                if ($hospede->create()) {
                    $message = "Hóspede criado com sucesso!";
                } else {
                    $message = "Erro ao criar hóspede.";
                }
                break;
                
            case 'update':
                $hospede->id = $_POST['id'];
                $hospede->nome = $_POST['nome'];
                $hospede->sobrenome = $_POST['sobrenome'];
                $hospede->documento = $_POST['documento'];
                $hospede->email = $_POST['email'];
                $hospede->telefone = $_POST['telefone'];
                
                if ($hospede->update()) {
                    $message = "Hóspede atualizado com sucesso!";
                } else {
                    $message = "Erro ao atualizar hóspede.";
                }
                break;
                
            case 'delete':
                $hospede->id = $_POST['id'];
                if ($hospede->delete()) {
                    $message = "Hóspede deletado com sucesso!";
                } else {
                    $message = "Erro ao deletar hóspede.";
                }
                break;
        }
    }
}

// Buscar hóspede para edição
$edit_hospede = null;
if (isset($_GET['edit'])) {
    $hospede->id = $_GET['edit'];
    if ($hospede->readOne()) {
        $edit_hospede = $hospede;
    }
}

// Listar todos os hóspedes
$stmt = $hospede->read();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Hóspedes - Sistema de Gestão de Hotéis</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <h1>Sistema de Gestão de Hotéis</h1>
        <nav>
            <ul>
                <li><a href="../index.php">Início</a></li>
                <li><a href="quartos.php">Quartos</a></li>
                <li><a href="hospedes.php" class="active">Hóspedes</a></li>
                <li><a href="reservas.php">Reservas</a></li>
                <li><a href="estadias.php">Estadias</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="container">
            <h2>Gerenciar Hóspedes</h2>
            
            <?php if ($message): ?>
                <div class="message"><?php echo $message; ?></div>
            <?php endif; ?>

            <!-- Formulário para criar/editar hóspede -->
            <div class="form-section">
                <h3><?php echo $edit_hospede ? 'Editar Hóspede' : 'Novo Hóspede'; ?></h3>
                <form method="POST">
                    <input type="hidden" name="action" value="<?php echo $edit_hospede ? 'update' : 'create'; ?>">
                    <?php if ($edit_hospede): ?>
                        <input type="hidden" name="id" value="<?php echo $edit_hospede->id; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="nome">Nome:</label>
                        <input type="text" id="nome" name="nome" value="<?php echo $edit_hospede ? $edit_hospede->nome : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="sobrenome">Sobrenome:</label>
                        <input type="text" id="sobrenome" name="sobrenome" value="<?php echo $edit_hospede ? $edit_hospede->sobrenome : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="documento">Documento (CPF/RG):</label>
                        <input type="text" id="documento" name="documento" value="<?php echo $edit_hospede ? $edit_hospede->documento : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">E-mail:</label>
                        <input type="email" id="email" name="email" value="<?php echo $edit_hospede ? $edit_hospede->email : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="telefone">Telefone:</label>
                        <input type="tel" id="telefone" name="telefone" value="<?php echo $edit_hospede ? $edit_hospede->telefone : ''; ?>">
                    </div>
                    
                    <button type="submit" class="btn"><?php echo $edit_hospede ? 'Atualizar' : 'Criar'; ?></button>
                    <?php if ($edit_hospede): ?>
                        <a href="hospedes.php" class="btn btn-secondary">Cancelar</a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Lista de hóspedes -->
            <div class="table-section">
                <h3>Hóspedes Cadastrados</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Documento</th>
                            <th>E-mail</th>
                            <th>Telefone</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['nome'] . ' ' . $row['sobrenome']); ?></td>
                            <td><?php echo htmlspecialchars($row['documento']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['telefone']); ?></td>
                            <td>
                                <a href="hospedes.php?edit=<?php echo $row['id']; ?>" class="btn btn-small">Editar</a>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja deletar este hóspede?');">
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

