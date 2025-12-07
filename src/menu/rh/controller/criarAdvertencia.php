<?php
    ob_start(); // ob_start para evitar problemas com headers

    session_start(); // Iniiciar sessão
    require_once __DIR__ . '/../../../include/conexao.php';
    require_once __DIR__ . '/../repository/advertenciaRepository.php';

    // Só processa quando for POST (evita avisos de array indefinido quando a página é acessada via GET)
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ../../criarAdvertencia.php');
        exit;
    }

    try{
        // usar null coalescing para evitar avisos
        $membro_id = isset($_POST['membro_id']) ? (int) $_POST['membro_id'] : null;
        $diretor_id = isset($_POST['diretor_id']) ? (int) $_POST['diretor_id'] : null;
        $motivo = trim($_POST['motivo'] ?? '');
        $acao_corretiva = trim($_POST['acao_corretiva'] ?? '');
        $data = trim($_POST['data'] ?? null);

        if(!$membro_id){
            throw new Exception("Membro é obrigatório");
        }

        if($motivo === ''){
            throw new Exception("Motivo é obrigatório");
        }

        $advertencia = new stdClass();
        $advertencia->membro_id = $membro_id;
        $advertencia->diretor_id = $diretor_id;
        $advertencia->motivo = $motivo;
        $advertencia->acao_corretiva = $acao_corretiva;
        $advertencia->data = $data ?: null;

        // chama a função do repositório passando a conexão PDO
        $success = insertNewAdvertencia($pdo, $advertencia);

        if ($success) {
            $_SESSION['flash_success'] = 'Advertência criada com sucesso.';
            $response['success'] = true;
            $response['message'] = 'Advertência criada com sucesso.';
            header('Location: ../criarAdvertencia.php?success=' . urlencode($response['message']));
            exit;
        } else {
            throw new Exception('Falha ao inserir advertência');
        }

    } catch(Exception $e){
        // registra mensagem de erro e redireciona de volta
        $_SESSION['flash_error'] = $e->getMessage();
        header('Location: ../../criarAdvertencia.php?error=' . urlencode($e->getMessage()));
        exit;
    }


?>
