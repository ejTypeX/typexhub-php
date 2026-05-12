<?php 
session_start();
// if (!isset($_SESSION['usuario_id'])) {
//     header('Location: ../../auth/login.php');
//     exit;
// }

if (isset($_GET['acao']) && $_GET['acao'] === 'entrada') {
    $host = "db:3306";
    $dbname = "typex";
    $user = "root";
    $pass = "masterkey";

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT SUM(valor_principal) as total FROM contas_a_receber";
        $query = $pdo->query($sql);
        $resultado = $query->fetch(PDO::FETCH_ASSOC);

        $total = (float) ($resultado['total'] ?? 0);

        header('Content-Type: application/json');
        echo json_encode(['total' => $total]);
        exit;
    } catch (PDOException $e) {
        header('Content-Type: application/json');
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}
if (isset($_GET['acao']) && $_GET['acao'] === 'saida') {
    $host = "db:3306";
    $dbname = "typex";
    $user = "root";
    $pass = "masterkey";

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT SUM(valor_principal) as total FROM contas_a_pagar";
        $query = $pdo->query($sql);
        $resultado = $query->fetch(PDO::FETCH_ASSOC);
        $total = -(float) ($resultado['total'] ?? 0);

        header('Content-Type: application/json');
        echo json_encode(['total' => $total]);
        exit;
    } catch (PDOException $e) {
        header('Content-Type: application/json');
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}

if (isset($_GET['acao']) && $_GET['acao'] === 'movimentacoes_recentes') {
    $host = "db:3306";
    $dbname = "typex";
    $user = "root";
    $pass = "masterkey";

    $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $itens_por_pagina = 5;
    $offset = ($pagina - 1) * $itens_por_pagina;

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $limit_busca = $itens_por_pagina + 1;
        $sql = "SELECT 'entrada' as tipo, `observacao`, `data_emissao`, `valor_principal` 
                FROM `contas_a_receber` WHERE `status` = 'pago' 
                UNION ALL 
                SELECT 'saida' as tipo, `observacao`, `data_emissao`, `valor_principal` 
                FROM `contas_a_pagar` WHERE `status` = 'pago' 
                ORDER BY `data_emissao` DESC 
                LIMIT $limit_busca OFFSET $offset";
        $query = $pdo ->query($sql);
        $movimentacoes_recentes = $query->fetchAll(PDO::FETCH_ASSOC);
        
        header('Content-Type: application/json');
        echo json_encode($movimentacoes_recentes);
        exit;
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}


include "../../include/header.php";


?>

<script>
    let totalEntrada = 0;
    let totalSaida = 0;
    let paginaAtual = 1;

    function atualizarEntrada() {
        return fetch('financas.php?acao=entrada')
            .then(response => response.json())
            .then(data => {
                if (data.total !== undefined) {
                    totalEntrada = parseFloat(data.total) || 0; 
                    document.getElementById('valor-entrada').innerText = totalEntrada.toLocaleString('pt-BR', {
                        style: 'currency',
                        currency: 'BRL'
                    });
                }
            })
            .catch(error => {
                console.error('Erro Entrada:', error);
                document.getElementById('valor-entrada').innerText = "Erro";
            });
    }

    function atualizarSaida() {
        return fetch('financas.php?acao=saida')
            .then(response => response.json())
            .then(data => {
                if (data.total !== undefined) {
                    totalSaida = parseFloat(data.total) || 0;
                    document.getElementById('valor-saida').innerText = totalSaida.toLocaleString('pt-BR', {
                        style: 'currency',
                        currency: 'BRL'
                    });
                }
            })
            .catch(error => {
                console.error('Erro Saída:', error);
                document.getElementById('valor-saida').innerText = "Erro";
            });
    }

    function atualizarSaldo() {
        const saldo = totalEntrada + totalSaida;
        const elementoSaldo = document.getElementById('valor-saldo');

        if (elementoSaldo) {
            elementoSaldo.innerText = saldo.toLocaleString('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            });
        }
    }

    function atualizarMovimentacoesRecentes() {
        fetch(`financas.php?acao=movimentacoes_recentes&pagina=${paginaAtual}`)
            .then(response => response.json())
            .then(dados => {
                const container = document.getElementById('lista-movimentacoes');
                const btnAnterior = document.getElementById('btn-anterior');
                const btnProximo = document.getElementById('btn-proximo');
                const txtPagina = document.getElementById('num-pagina');

                container.innerHTML = '';
                txtPagina.innerText = `Página ${paginaAtual}`;

                btnAnterior.disabled = paginaAtual === 1;

                if(!dados || dados.length === 0) {
                    if(paginaAtual === 1) {
                        container.innerHTML = `<div class="fin_empty_state"><p class="fin_empty_text">Nenhuma movimentação encontrada!</p></div>`;
                    }
                    btnProximo.disabled = true;
                    return;
                }

                if (dados.length > 5) {
                    btnProximo.disabled = false;
                    dados.pop();
                } else {
                    btnProximo.disabled = true;    
                }

                dados.forEach(t => {
                    const isEntrada = t.tipo === 'entrada';
                    const cardHtml = `
                    <div class="fin_card_mov">
                        <div class="fin_info_mov">
                            <h2 class="fin_h2_mov_nome">${t.observacao || 'Sem observação'}</h2>
                            <p class="fin_p_mov_data">${new Date(t.data_emissao).toLocaleDateString('pt-BR')}</p>
                        </div>
                        <div class="fin_valor_mov ${isEntrada ? 'fin_entrada_bg' : 'fin_saida_bg'}">
                            <p>${isEntrada ? '+ ' : '- '} ${parseFloat(t.valor_principal).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'})}</p>
                        </div>
                    </div>`;
                    container.innerHTML += cardHtml;
                });
            });
    }

    function mudarPagina(direcao) {
        paginaAtual += direcao;
        atualizarMovimentacoesRecentes();
    }


    document.addEventListener('DOMContentLoaded', () => {
        Promise.all([atualizarEntrada(), atualizarSaida()])
            .then(() => {
                atualizarSaldo();
                atualizarMovimentacoesRecentes();
            });
    });
    
</script>
<section class="fin_totais">
    <div class="fin_totais_container">
        <div class="fin_title">
            <h1 class="fin_h1_title" >Finanças</h1>
            <h2 class="fin_h2_title">Controle financeiro e orçamentário</h2>
        </div>
        <div class="fin_resultados">
            <div class="fin_card_entradas">
                <h2 class="fin_h2_entradas">Entradas</h2>
                <p class="fin_p_entradas" id="valor-entrada">Carregando...</p>
            </div>
            <div class="fin_card_saidas">
                <h2 class="fin_h2_saidas">Saídas</h2>
                <p class="fin_p_saidas" id="valor-saida">Carregando...</p>
            </div>
            <div class="fin_card_saldo">
                <h2 class="fin_h2_saldo">Saldo</h2>
                <p class="fin_p_saldo" id="valor-saldo">Carregando...</p>
            </div>
        </div>
    </div>
</section>
<section class="fin_movimentacoes">
    <div class="fin_movimentacoes_container">
        <div class="fin_header_mov">
            <h1 class="fin_h1_movimentacoes">Movimentações Recentes</h1>
            <div class="fin_paginacao">
                <button id="btn-anterior" onclick="mudarPagina(-1)"><-</button>
                <span id="num-pagina">Página 1</span>
                <button id="btn-proximo" onclick="mudarPagina(1)">-></button>
            </div>
        </div>
        <div class="fin_lista_movimentacoes" id="lista-movimentacoes">
            <p style="color: #ababab; padding: 20px;">Carregando movimentações...</p>
        </div>
    </div>
</section>