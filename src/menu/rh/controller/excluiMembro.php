<?php
    ob_start(); // ob_start para evitar problemas com headers

    session_start(); // Iniiciar sessão
    require_once __DIR__ . '/../../../include/conexao.php';
    require_once __DIR__ . '/../repository/membroRepository.php';

   

    try{
        // usar null coalescing para evitar avisos
        $id = trim($_GET['id'] ?? '');
        
        $sucesse = deleteMembro($pdo, $id);

        if ($success) {
            $_SESSION['flash_success'] = 'Membro alterado com sucesso.';
            $response['success'] = true;
            $response['message'] = 'Membro alterado com sucesso.';
            header('Location: ../listaMembro.php?success=' . urlencode($response['message']));
            exit;
        } else {
            throw new Exception($success);

        }

    } catch(Exception $e){
        // registra mensagem de erro e redireciona de volta
        $_SESSION['flash_error'] = $e->getMessage();
        header('Location: ../listaMembro.php?error=' . urlencode($e->getMessage()));
        exit;
    }




?>