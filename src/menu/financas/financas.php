<?php 
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../../auth/login.php');
    exit;
}
include "../../include/header.php";
?>

<script>
    let totalEntrada = 0;
    let totalSaida = 0;

    function atualizarEntrada() {
        return fetch('get_entrada.php')
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
        return fetch('get_saida.php')
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

    document.addEventListener('DOMContentLoaded', () => {
        Promise.all([atualizarEntrada(), atualizarSaida()])
            .then(() => {
                atualizarSaldo();
            })
            .catch(err => console.error("Erro no fluxo:", err));
    });
    
</script>

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