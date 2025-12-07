<?php

function insertNewreconhecimento($pdo, $ObjReconhecimento)
{
    
    $stmt = $pdo->prepare("INSERT INTO reconhecimento (data_reconhecimento, diretor_id, feito, membro_id, reconhecimento) 
    VALUES (?, ?, ?, ?, ?)");
   return $stmt->execute([
        $ObjReconhecimento->data_reconhecimento,
        $ObjReconhecimento->diretor_id,
        $ObjReconhecimento->feito,
        $ObjReconhecimento->membro_id,
        $ObjReconhecimento->reconhecimento,
    ]);
}


function findAll($pdo)
{
    $stmt = $pdo->prepare("SELECT r.*, m.nome as membro_nome, d.dir_nome as diretor_noma  FROM reconhecimento r
                            LEFT JOIN membro m ON r.membro_id = m.id
                            LEFT JOIN diretoria d ON r.diretor_id = d.dir_id");
    return $stmt->execute() ? $stmt->fetchAll() : [];
}


function findOneById($pdo, $id)
{
    $stmt = $pdo->prepare("SELECT * FROM reconhecimento WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}


function updateReconhecimento($pdo, $id, $reconhecimento)
{
        $stmt = $pdo->prepare("UPDATE reconhecimento SET data_reconhecimento = ?, diretor_id = ?, feito = ?, membro_id = ?, reconhecimento = ?, updated_at = ? WHERE id = ?");
        return $stmt->execute([
            $reconhecimento->data_reconhecimento,
            $reconhecimento->diretor_id,
            $reconhecimento->feito,
            $reconhecimento->membro_id,
            $reconhecimento->reconhecimento,
            $reconhecimento->update_at,
            $id
        ]);
    


}

function deleteReconhecimento($pdo, $id)
{
    try {
        $stmt = $pdo->prepare("DELETE FROM reconhecimento WHERE id = ?");
        $stmt->execute([$id]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function selectAllMember($pdo){
    $stmt = $pdo->prepare("SELECT id, nome FROM membro");
    $stmt->execute();
    return $stmt->fetchAll();
}

function selectAllDirector($pdo){
    $stmt = $pdo->prepare("SELECT dir_id, dir_nome FROM diretoria");
    $stmt->execute();
    return $stmt->fetchAll();
}


?>