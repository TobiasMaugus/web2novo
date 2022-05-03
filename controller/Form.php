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
    $form->set("id", "");
    $form->set("prato", "");
    $form->set("ingredientes", "");
    $form->set("preco", "");
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
        if (empty($_POST["id"])) {
          $cardapio->insert(
            "prato, ingredientes, preco",
            "$prato, $ingredientes, $preco"
          );
        } else {
          $id = $conexao->quote($_POST["id"]);
          $cardapio->update(
            "prato = $prato, ingredientes = $ingredientes, preco = $preco",
            "id = $id"
          );
        }
      } catch (Exception $e) {
        echo $e->getMessage();
      }
    }
  }

  public function editar()
  {
    if (isset($_GET["id"])) {
      try {
        $conexao = Transaction::get();
        $id = $conexao->quote($_GET["id"]);
        $cardapio = new Crud("cardapio");
        $resultado = $cardapio->select("*", "id = $id");
        $form = new Template("view/form.html");
        foreach ($resultado[0] as $cod => $valor) {
          $form->set($cod, $valor);
        } 
        $this->message = $form->saida();
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