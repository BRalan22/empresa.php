<?php

// clinte
abstract class Cliente {
    public $nome;
    public function __construct($nome) { $this->nome = $nome; }
    abstract public function aplicarDesconto($valor);
}


class ClienteComum extends Cliente {
    public function aplicarDesconto($valor) { return $valor; } // Sem desconto
}

class ClientePremium extends Cliente {
    public function aplicarDesconto($valor) { return $valor * 0.90; } // 20% de desconto
}

// produto
class Produto {
    public $nome;
    public $preco;
    public $estoque;

    public function __construct($nome, $preco, $estoque) {
        $this->nome = $nome;
        $this->preco = $preco;
        $this->estoque = $estoque;
    }
}

// pedido
class Pedido {
    private $cliente;
    private $itens = [];
    public $status = "Aberto";

    public function __construct(Cliente $cliente) {
        $this->cliente = $cliente;
    }

    public function adicionar(Produto $p, $qtd) {
        if ($p->estoque >= $qtd) {
            $this->itens[] = ['prod' => $p, 'qtd' => $qtd];
            $p->estoque -= $qtd;
        } else {
            echo "Erro: Sem estoque para {$p->nome}\n";
        }
    }

    public function finalizar() {
        $total = 0;
        foreach ($this->itens as $i) {
            $total += $i['prod']->preco * $i['qtd'];
        }

        $valorFinal = $this->cliente->aplicarDesconto($total);
        $this->status = "Pago";

        echo "Pedido de: {$this->cliente->nome}\n";
        echo "Total com Desconto: R$ " . number_format($valorFinal, 2, ',', '.') . "\n";
        echo "Status: {$this->status}\n---\n";
    }
}



$p1 = new Produto("Celular", 1000, 10);
$p2 = new Produto("Fone", 200, 5);


$jose = new ClienteComum("José");
$ped1 = new Pedido($jose);
$ped1->adicionar($p1, 1);
$ped1->finalizar();


$maria = new ClientePremium("Maria");
$ped2 = new Pedido($maria);
$ped2->adicionar($p1, 1);
$ped2->adicionar($p2, 1);

$ped2->finalizar();
