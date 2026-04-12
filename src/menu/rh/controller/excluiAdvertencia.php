<?php
    ob_start(); // ob_start para evitar problemas com headers

    session_start(); // Iniiciar sessão
    require_once __DIR__ . '/../../../include/conexao.php';
    require_once __DIR__ . '/../repository/advertenciaRepository.php';

    try{
        // usar null coalescing para evitar avisos
        $id = trim($_GET['id'] ?? '');

        if(!$id){
            throw new Exception("ID inválido");
        }

        $success = deleteAdvertencia($pdo, $id);

        if ($success) {
            $_SESSION['flash_success'] = 'Advertência excluída com sucesso.';
            $response['success'] = true;
            $response['message'] = 'Advertência excluída com sucesso.';
            header('Location: ../listaAdvertencia.php?success=' . urlencode($response['message']));
            exit;
        } else {
            throw new Exception('Falha ao excluir advertência');
        }

    } catch(Exception $e){
        // registra mensagem de erro e redireciona de volta
        $_SESSION['flash_error'] = $e->getMessage();
        header('Location: ../listaAdvertencia.php?error=' . urlencode($e->getMessage()));
        exit;
    }


?>
