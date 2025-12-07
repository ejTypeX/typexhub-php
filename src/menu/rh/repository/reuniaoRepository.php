<?php

/**
 * Repository for `reuniao` table.
 * Schema (reference):
 * CREATE TABLE reuniao (
 *     id INT AUTO_INCREMENT PRIMARY KEY,
 *     motivo VARCHAR(255) NOT NULL,
 *     data_reuniao DATE NOT NULL,
 *     local_reuniao VARCHAR(255) NOT NULL,
 *     horas VARCHAR(50) NOT NULL,
 *     descricao TEXT,
 *     status_reuniao VARCHAR(20) NOT NULL,
 *     created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
 *     updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
 * );
 */

function insertNewReuniao($pdo, $reuniao)
{
    $stmt = $pdo->prepare("INSERT INTO reuniao (motivo, data_reuniao, local_reuniao, horas, descricao, status_reuniao) VALUES (?, ?, ?, ?, ?, ?)");
    return $stmt->execute([
        $reuniao->motivo ?? null,
        $reuniao->data_reuniao ?? null,
        $reuniao->local_reuniao ?? null,
        $reuniao->horas ?? null,
        $reuniao->descricao ?? null,
        $reuniao->status_reuniao ?? 'Programado',
    ]);
}

function getAllReunioes($pdo)
{
    $stmt = $pdo->prepare("SELECT * FROM reuniao ORDER BY data_reuniao DESC");
    if ($stmt->execute()) {
        return $stmt->fetchAll();
    }
    return [];
}

function getReuniaoById($pdo, $id)
{
    $stmt = $pdo->prepare("SELECT * FROM reuniao WHERE id = ? LIMIT 1");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function updateReuniao($pdo, $id, $reuniao)
{
    $stmt = $pdo->prepare("UPDATE reuniao SET motivo = ?, data_reuniao = ?, local_reuniao = ?, horas = ?, descricao = ?, status_reuniao = ? WHERE id = ?");
    return $stmt->execute([
        $reuniao->motivo ?? null,
        $reuniao->data_reuniao ?? null,
        $reuniao->local_reuniao ?? null,
        $reuniao->horas ?? null,
        $reuniao->descricao ?? null,
        $reuniao->status_reuniao ?? 'Programado',
        $id,
    ]);
}

function deleteReuniao($pdo, $id)
{
    try {
        $stmt = $pdo->prepare("DELETE FROM reuniao WHERE id = ?");
        $stmt->execute([$id]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

?>
