<?php
    ob_start(); // ob_start para evitar problemas com headers

    session_start(); // Iniciar sessão
    require_once __DIR__ . '/../../../include/conexao.php';
    require_once __DIR__ . '/../repository/reuniaoRepository.php';

    try{
        // usar null coalescing para evitar avisos
        $id = trim($_GET['id'] ?? '');

        if(!$id){
            throw new Exception("ID inválido");
        }

        $success = deleteReuniao($pdo, $id);

        if ($success) {
            $_SESSION['flash_success'] = 'Reunião excluída com sucesso.';
            $response['success'] = true;
            $response['message'] = 'Reunião excluída com sucesso.';
            header('Location: ../listaReuniao.php?success=' . urlencode($response['message']));
            exit;
        } else {
            throw new Exception('Falha ao excluir reunião');
        }

    } catch(Exception $e){
        // registra mensagem de erro e redireciona de volta
        $_SESSION['flash_error'] = $e->getMessage();
        header('Location: ../listaReuniao.php?error=' . urlencode($e->getMessage()));
        exit;
    }

?>
