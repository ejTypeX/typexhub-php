<?php
ob_start(); // ob_start para evitar problemas com headers

session_start(); // Iniiciar sessão
include "../../include/conexao.php";

/* if (!isset($_SESSION['usuario'])) {
    ob_end_clean(); // Limpar buffer antes do redirect
    header('Location: ../../auth/login.php');
    exit;
}
*/

$response = ['success' => false, 'message' => ''];

try {
    // Validar dados recebidos
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método não permitido');
    }

    // Campos obrigatórios
    $usr_nome = trim($_POST['usr_nome'] ?? '');
    $usr_email = trim($_POST['usr_email'] ?? '');
    $usr_senha = $_POST['usr_senha'] ?? '';
    $confirma_senha = $_POST['confirma_senha'] ?? '';

    if (empty($usr_nome) || empty($usr_email) || empty($usr_senha)) {
        throw new Exception('Nome, e-mail e senha são obrigatórios');
    }

    // Validar confirmação de senha
    if ($usr_senha !== $confirma_senha) {
        throw new Exception('As senhas não coincidem');
    }

    // Validar tamanho mínimo da senha
    if (strlen($usr_senha) < 6) {
        throw new Exception('A senha deve ter pelo menos 6 caracteres');
    }

    // Validar e-mail
    if (!filter_var($usr_email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('E-mail inválido');
    }

    // Campos opcionais
    $usr_documento = trim($_POST['usr_documento'] ?? '');
    $usr_ra = trim($_POST['usr_ra'] ?? '');
    $user_role_usr_id = !empty($_POST['user_role_usr_id']) ? (int)$_POST['user_role_usr_id'] : null;
    $usr_ativo = isset($_POST['usr_ativo']) ? 1 : 0;

    // Verificar se e-mail já existe
    $stmt = $pdo->prepare("SELECT usr_id FROM usuario WHERE usr_email = ?");
    $stmt->execute([$usr_email]);
    if ($stmt->fetch()) {
        throw new Exception('Este e-mail já está cadastrado');
    }

    // Verificar se RA já existe (se fornecido)
    if (!empty($usr_ra)) {
        $stmt = $pdo->prepare("SELECT usr_id FROM usuario WHERE usr_ra = ?");
        $stmt->execute([$usr_ra]);
        if ($stmt->fetch()) {
            throw new Exception('Este RA já está cadastrado');
        }
    }

    // Processar upload de foto
    $usr_foto = null;
    if (isset($_FILES['usr_foto']) && $_FILES['usr_foto']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../../assets/images/usuarios/';
        
        // Criar diretório se não existir
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileInfo = pathinfo($_FILES['usr_foto']['name']);
        $extension = strtolower($fileInfo['extension']);
        
        // Validar extensão
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($extension, $allowedExtensions)) {
            throw new Exception('Formato de imagem não permitido. Use JPG, PNG ou GIF');
        }

        // Validar tamanho (máximo 5MB)
        if ($_FILES['usr_foto']['size'] > 5 * 1024 * 1024) {
            throw new Exception('Imagem muito grande. Tamanho máximo: 5MB');
        }

        // Gerar nome único para o arquivo
        $fileName = uniqid() . '.' . $extension;
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['usr_foto']['tmp_name'], $filePath)) {
            $usr_foto = 'assets/images/usuarios/' . $fileName;
        } else {
            throw new Exception('Erro ao fazer upload da imagem');
        }
    }

    // Hash da senha
    $senha_hash = password_hash($usr_senha, PASSWORD_DEFAULT);

    // Inserir usuário no banco
    $sql = "INSERT INTO usuario (usr_nome, usr_documento, usr_email, usr_senha, usr_ra, usr_foto, user_role_usr_id, usr_ativo) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $usr_nome,
        $usr_documento ?: null,
        $usr_email,
        $senha_hash,
        $usr_ra ?: null,
        $usr_foto,
        $user_role_usr_id,
        $usr_ativo
    ]);

    $response['success'] = true;
    $response['message'] = 'Usuário cadastrado com sucesso!';
    $response['user_id'] = $pdo->lastInsertId();

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
} catch (PDOException $e) {
    $response['message'] = 'Erro no banco de dados: ' . $e->getMessage();
}

// Se for uma requisição AJAX, retornar JSON
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    ob_end_clean(); // Limpar buffer antes de enviar JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Limpar buffer antes do redirecionamento
ob_end_clean();

// Redirecionamento com mensagem
if ($response['success']) {
    header('Location: rh.php?success=' . urlencode($response['message']));
} else {
    header('Location: rh.php?error=' . urlencode($response['message']));
}
exit;
?>