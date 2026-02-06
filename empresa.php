<?php


abstract class Cliente {
    public string $nome;
    public string $email;
    public string $cpf;

    public function __construct($nome, $email, $cpf) {
        $this->nome = $nome;
        $this->email = $email;
        $this->cpf = $cpf;
    }

    abstract public function aplicarDesconto($valor);
}


class ClienteComum extends Cliente {
    public function aplicarDesconto($valor) { 
        return $valor; 
    }
}

class ClientePremium extends Cliente {
    public function aplicarDesconto($valor) { 
        return $valor * 0.90; 
    }
}


class Produto {
    private string $nome;
    private float $preco;
    public int $estoque;

    public function __construct($nome, $preco, $estoque) {
        $this->nome = $nome;
        $this->preco = $preco;
        $this->estoque = $estoque;
    }

    public function getNome() { return $this->nome; }
    public function getPreco() { return $this->preco; }
}


class Pedido {
    private Cliente $cliente;
    private array $itens = [];
    public string $status = "Aberto";

    public function __construct(Cliente $cliente) {
        $this->cliente = $cliente;
    }

    public function adicionar(Produto $p, $qtd) {
        if ($p->estoque >= $qtd) {
            $this->itens[] = ['prod' => $p, 'qtd' => $qtd];
            $p->estoque -= $qtd;
        }
    }

    public function finalizar() {
        $total = 0;
        foreach ($this->itens as $item) {
            $total += $item['prod']->getPreco() * $item['qtd'];
        }

        $valorFinal = $this->cliente->aplicarDesconto($total);
        $this->status = "Pago";

        echo "--- TOTAL DAS COMPRAS DOS CLIENTES ---\n";
        echo "Cliente: {$this->cliente->nome} | CPF: {$this->cliente->cpf}\n";
        echo "Email: {$this->cliente->email}\n";
        echo "Total: R$ " . number_format($valorFinal, 2) . "\n";
        echo "Status: {$this->status}\n";
        echo "------------------------\n\n";
    }
}




$celular = new Produto("iPhone", 5000, 5);


$premium = new ClientePremium("Ana Silva", "Ana.Silva@email.com", "123.456.789-00");
$pedido1 = new Pedido($premium);
$pedido1->adicionar($celular, 1);
$pedido1->finalizar();


$comum = new ClienteComum("Lucas Santos", "Lucas.Santos@email.com", "987.654.321-11");
$pedido2 = new Pedido($comum);
$pedido2->adicionar($celular, 1);

$pedido2->finalizar();




