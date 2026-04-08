<?php
    ob_start(); // evitar problemas com headers
    session_start();

    require_once __DIR__ . '/../../../include/conexao.php';
    require_once __DIR__ . '/../repository/reconhecimentoRepository.php';

    try {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if ($id <= 0) {
            throw new Exception('ID inválido');
        }

        $success = deleteReconhecimento($pdo, $id);

        if ($success) {
            $_SESSION['flash_success'] = 'Reconhecimento excluído com sucesso.';
             $response['success'] = true;
            $response['message'] = 'Reconhecimento excluido com sucesso.';
            header('Location: ../listaReconhecimento.php?success=' . urlencode('Excluído com sucesso'));
            exit;
        }

        throw new Exception('Falha ao excluir reconhecimento');

    } catch (Exception $e) {
        $_SESSION['flash_error'] = $e->getMessage();
        header('Location: ../listaReconhecimento.php?error=' . urlencode($e->getMessage()));
        exit;
    }

?>
