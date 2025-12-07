<?php
    ob_start(); // ob_start para evitar problemas com headers

    session_start(); // Iniiciar sessão
    require_once __DIR__ . '/../../../include/conexao.php';
    require_once __DIR__ . '/../repository/reconhecimentoRepository.php';

    

    try{
        $member_id = trim($_POST['member_id'] ?? '');
        $director_id = trim($_POST['director_id'] ?? '');
        $feito = trim($_POST['feito'] ?? '');
        $reconhecimento = trim($_POST['reconhecimento'] ?? '');
        $dataReconhecimento = trim($_POST['data_reconhecimento'] ?? '');
        $id = trim($_POST['id'] ?? '');

        if(!$member_id){
            throw new Exception("Membro é obrigatório");
        }

        //if(!$director_id){
          //  throw new Exception("Diretor é obrigatório");
        //}

        if(!$feito || $feito === ''){
            throw new Exception("Feito é obrigatório");
        }


        if(!$dataReconhecimento || $dataReconhecimento === ''){
            throw new Exception("A data é obrigatório");
        }

        if(!$reconhecimento || $reconhecimento === ''){
            throw new Exception("Habilidade é obrigatória");
        }

        $ObjReconhecimento = new stdClass();
        $ObjReconhecimento->data_reconhecimento = $dataReconhecimento;
        $ObjReconhecimento->diretor_id = null;
        $ObjReconhecimento->feito = $feito;
        $ObjReconhecimento->membro_id = $member_id;
        $ObjReconhecimento->reconhecimento = $reconhecimento;
        $ObjReconhecimento->update_at = date('Y-m-d H:i:s');
        


        // chama a função do repositório passando a conexão PDO
        $success = updateReconhecimento($pdo, $id,$ObjReconhecimento);

        if ($success) {
            $_SESSION['flash_success'] = 'Reconhecimento alterado com sucesso.';
            $response['success'] = true;
            $response['message'] = 'Reconhecimento alterado com sucesso.';
            header('Location: ../listaReconhecimento.php?success=' . urlencode($response['message']));
            exit;
        } else {
            throw new Exception('Falha ao excluir reconhecimento');
        }

    } catch(Exception $e){
        // registra mensagem de erro e redireciona de volta
        $_SESSION['flash_error'] = $e->getMessage();
        header('Location: ../editarReconhecimento.php?id=  ' . $id. '?error=' . urlencode($e->getMessage()));
        exit;
    }




?>