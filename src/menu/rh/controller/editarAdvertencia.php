<?php
    ob_start(); // ob_start para evitar problemas com headers

    session_start(); // Iniiciar sessão
    require_once __DIR__ . '/../../../include/conexao.php';
    require_once __DIR__ . '/../repository/advertenciaRepository.php';

    // Só processa quando for POST (evita avisos de array indefinido quando a página é acessada via GET)
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ../../editarAdvertencia.php');
        exit;
    }

    try{
        // usar null coalescing para evitar avisos
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        $membro_id = isset($_POST['membro_id']) ? (int) $_POST['membro_id'] : null;
        $diretor_id = isset($_POST['diretor_id']) ? (int) $_POST['diretor_id'] : null;
        $motivo = trim($_POST['motivo'] ?? '');
        $acao_corretiva = trim($_POST['acao_corretiva'] ?? '');
        $data = trim($_POST['data'] ?? null);

        if($id <= 0){
            throw new Exception("ID inválido");
        }

        if(!$membro_id){
            throw new Exception("Membro é obrigatório");
        }

        $advertencia = new stdClass();
        $advertencia->membro_id = $membro_id;
        $advertencia->diretor_id = $diretor_id;
        $advertencia->motivo = $motivo;
        $advertencia->acao_corretiva = $acao_corretiva;
        $advertencia->data = $data ?: null;

        // chama a função do repositório passando a conexão PDO
        $success = updateAdvertencia($pdo, $id, $advertencia);

        if ($success) {
            $_SESSION['flash_success'] = 'Advertência atualizada com sucesso.';
            $response['success'] = true;
            $response['message'] = 'Advertência atualizada com sucesso.';
            header('Location: ../editarAdvertencia.php?id= '.$id.'?success=' . urlencode($response['message']));
            exit;
        } else {
            throw new Exception('Falha ao atualizar advertência');
        }

    } catch(Exception $e){
        // registra mensagem de erro e redireciona de volta
        $_SESSION['flash_error'] = $e->getMessage();
        header('Location: ../../editarAdvertencia.php?id= '.$id.'?error=' . urlencode($e->getMessage()));
        exit;
    }


?>
