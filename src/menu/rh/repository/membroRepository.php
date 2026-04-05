<?php

function insertNewMembro($pdo, $membro)
{

    $stmt = $pdo->prepare("INSERT INTO membro (cpf, data_nascimento, email, github, habilidades, instagram, linkedin, nome, rg, whatsapp, admissao, ra, periodo, cargo, area, coeficiente, celular, endereco) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    return $stmt->execute([
        $membro->cpf,
        $membro->data_nascimento,
        $membro->email,
        $membro->github,
        $membro->habilidade,
        $membro->instagram,
        $membro->linkedin,
        $membro->nome,
        $membro->rg,
        $membro->whatsapp,
        $membro->admissao ?? null,
        $membro->ra ?? null,
        $membro->periodo ?? null,
        $membro->cargo ?? null,
        $membro->area ?? null,
        $membro->coeficiente ?? null,
        $membro->celular ?? null,
        $membro->endereco ?? null,
    ]);
}


function findAll($pdo)
{
    $stmt = $pdo->prepare("SELECT * FROM membro");
    return $stmt->execute() ? $stmt->fetchAll() : [];
}


function findOneById($pdo, $id)
{
    $stmt = $pdo->prepare("SELECT * FROM membro WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}


function updateMembro($pdo, $id, $membro)
{
    $stmt = $pdo->prepare("UPDATE membro SET cpf = ?, data_nascimento = ?, email = ?, github = ?, habilidades = ?, instagram = ?, linkedin = ?, nome = ?, rg = ?, whatsapp = ?, admissao = ?, ra = ?, periodo = ?, cargo = ?, area = ?, coeficiente = ?, celular = ?, endereco = ? WHERE id = ?");
    return $stmt->execute([
        $membro->cpf,
        $membro->data_nascimento,
        $membro->email,
        $membro->github,
        $membro->habilidade,
        $membro->instagram,
        $membro->linkedin,
        $membro->nome,
        $membro->rg,
        $membro->whatsapp,
        $membro->admissao ?? null,
        $membro->ra ?? null,
        $membro->periodo ?? null,
        $membro->cargo ?? null,
        $membro->area ?? null,
        $membro->coeficiente ?? null,
        $membro->celular ?? null,
        $membro->endereco ?? null,
        $id
    ]);
}

function deleteMembro($pdo, $id)
{
    try {
        $stmt = $pdo->prepare("DELETE FROM membro WHERE id = ?");
        $stmt->execute([$id]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}


?>