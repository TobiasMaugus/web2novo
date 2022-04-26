<?php
class Form
{
  private $message = "";
  public function __construct()
  {
    Transaction::open();
  }
    
  public function controller()
  {
    $form = new Template("view/form.html");
    $this->message = $form->saida();
  }
  public function salvar(){
    if(isset($_POST["prato"]) && isset($_POST["ingredientes"]) && isset($_POST["preco"])){
      try{
        $conexao = Transaction::get();
        $cardapio = new Crud("cardapio");
        $prato = $conexao->quote($_POST["prato"]);
        $ingredientes = $conexao->quote($_POST["ingredientes"]);
        $preco = $conexao->quote($_POST["preco"]);
        $resultado = $cardapio->insert("prato, ingredientes, preco", "$prato, $ingredientes, $preco");
      } catch (Exception $e) {
        echo $e->getMessage();
      }
    }
  }

  public function getMessage()
  {
    return $this->message;
  }

  public function __destruct()
  {
    Transaction::close();
  }
}