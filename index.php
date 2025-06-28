<!doctype html>
<html lang="pt-BR">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema de Gestão de Hotéis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/style.css">
  </head>
  <body class="container">

    <h1 class="mt-5 text-center">Sistema de Gestão de Hotéis</h1>

    <?php
        require_once('config/conexao.php');
        if ($_SERVER['REQUEST_METHOD'] == "POST"){
            try{
                $email = $_POST['email'];
                $senha = $_POST['senha'];
                $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE email = ?');
                $stmt->execute([$email]);
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($usuario && password_verify($senha, $usuario['senha'])){
                    session_start();
                    $_SESSION['usuario'] = $usuario['nome'];
                    $_SESSION['acesso'] = true;
                    $_SESSION['id'] = $usuario['id'];
                    header('location: principal.php'); 
                } else {
                    $mensagem['erro'] = "Usuário e/ou senha incorretos!";
                }
            } catch(Exception $e){
                echo "Erro: ".$e->getMessage();
                die();
            }
        }
    ?>

    <?php if (isset($mensagem['erro'])): ?>
        <div class="alert alert-danger mt-3 mb-3 text-center">
            <?= $mensagem['erro'] ?>
        </div>
    <?php endif; ?>

    <?php 
        if ((isset($_GET['mensagem'])) && ($_GET['mensagem'] == "acesso_negado")): ?>
        <div class="alert alert-danger mt-3 mb-3 text-center">
            Você precisa informar seus dados de acesso para acessar o sistema!
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-center mt-5">
        <div class="card p-4" style="max-width: 500px; width: 100%;">
            <div class="card-body">
                <h3 class="card-title text-center mb-4">Acesso ao Sistema</h3>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Informe o email</label>
                        <input id="email" name="email" class="form-control" type="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="senha" class="form-label">Informe a senha</label>
                        <input id="senha" name="senha" class="form-control" type="password" required>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn">Acessar</button>
                    </div>
                    <div class="text-center mt-3">
                        Não possui acesso? Clique <a href="novo_usuario.php">aqui</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  </body>
</html>
