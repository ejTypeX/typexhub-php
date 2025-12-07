<?php

/**
 * Repository for `advertencias` table.
 * Schema (reference):
 * create TABLE advertencias(
 *     id int AUTO_INCREMENT PRIMARY key,
 *     membro_id int,
 *     diretor_id int,
 *     motivo text,
 *     acao_corretiva text,
 *     data date,
 *     created_at TIMESTAMP  DEFAULT CURRENT_TIMESTAMP,
 *     updated_at TIMESTAMP,
 *     foreign key (membro_id) REFERENCES membro(id),
 *     foreign key (diretor_id) REFERENCES diretoria(dir_id)
 * );
 */

function insertNewAdvertencia($pdo, $advertencia)
{
    $stmt = $pdo->prepare("INSERT INTO advertencias (membro_id, diretor_id, motivo, acao_corretiva, data) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([
        $advertencia->membro_id ?? null,
        $advertencia->diretor_id ?? null,
        $advertencia->motivo ?? null,
        $advertencia->acao_corretiva ?? null,
        $advertencia->data ?? null,
    ]);
}

function getAllAdvertencias($pdo)
{
    $stmt = $pdo->prepare("SELECT a.*, m.nome AS membro_nome, d.dir_nome AS diretor_nome FROM advertencias a
        LEFT JOIN membro m ON a.membro_id = m.id
        LEFT JOIN diretoria d ON a.diretor_id = d.dir_id
        ORDER BY a.created_at DESC");
    if ($stmt->execute()) {
        return $stmt->fetchAll();
    }
    return [];
}

function getAdvertenciaById($pdo, $id)
{
    $stmt = $pdo->prepare("SELECT a.*, m.nome AS membro_nome, d.dir_nome AS diretor_nome FROM advertencias a
        LEFT JOIN membro m ON a.membro_id = m.id
        LEFT JOIN diretoria d ON a.diretor_id = d.dir_id
        WHERE a.id = ? LIMIT 1");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function updateAdvertencia($pdo, $id, $advertencia)
{
    $stmt = $pdo->prepare("UPDATE advertencias SET membro_id = ?, diretor_id = ?, motivo = ?, acao_corretiva = ?, data = ?, updated_at = NOW() WHERE id = ?");
    return $stmt->execute([
        $advertencia->membro_id ?? null,
        $advertencia->diretor_id ?? null,
        $advertencia->motivo ?? null,
        $advertencia->acao_corretiva ?? null,
        $advertencia->data ?? null,
        $id,
    ]);
}

function deleteAdvertencia($pdo, $id)
{
    try {
        $stmt = $pdo->prepare("DELETE FROM advertencias WHERE id = ?");
        $stmt->execute([$id]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function selectAllMember($pdo)
{
    $stmt = $pdo->prepare("SELECT id, nome FROM membro ORDER BY nome");
    $stmt->execute();
    return $stmt->fetchAll();
}

function selectAllDirector($pdo)
{
    $stmt = $pdo->prepare("SELECT dir_id, dir_nome FROM diretoria ORDER BY dir_nome");
    $stmt->execute();
    return $stmt->fetchAll();
}

?>