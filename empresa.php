<?php

//clinte
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
        return $valor; // Preço normal
    }
}

class ClientePremium extends Cliente {
    public function aplicarDesconto($valor) { 
        return $valor * 0.90; // 10% de desconto
    }
}

// produto
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

// pedido
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

        echo "--- RESUMO DO PEDIDO ---\n";
        echo "Cliente: {$this->cliente->nome} | CPF: {$this->cliente->cpf}\n";
        echo "Email: {$this->cliente->email}\n";
        echo "Total: R$ " . number_format($valorFinal, 2) . "\n";
        echo "Status: {$this->status}\n";
        echo "------------------------\n\n";
    }
}




$celular = new Produto("iPhone", 5000, 5);

// Cliente Premium  nome, email e cpf
$premium = new ClientePremium("Ana Costa", "ana@email.com", "123.456.789-00");
$pedido1 = new Pedido($premium);
$pedido1->adicionar($celular, 1);
$pedido1->finalizar();

// Cliente Comum  nome, email e cpf
$comum = new ClienteComum("Joao Silva", "joao@email.com", "987.654.321-11");
$pedido2 = new Pedido($comum);
$pedido2->adicionar($celular, 1);
$pedido2->finalizar();