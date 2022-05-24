<?php
class Form
{
  private $message = "";
  private $error = "";
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
  public function salvar()
  {
    if (isset($_POST["prato"]) && isset($_POST["ingredientes"]) && isset($_POST["preco"])) {
      try {
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
        $this->message = $cardapio->getMessage();
        $this->error = $cardapio->getError();
      } catch (Exception $e) {
        $this->message = $e->getMessage();
        $this->error = true;
      }
    } else {
      $this->message = "Campos não informados!";
      $this->error = true;
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
        if (!$cardapio->getError()) {
          $form = new Template("view/form.html");
          foreach ($resultado[0] as $cod => $valor) {
            $form->set($cod, $valor);
          }
          $this->message = $form->saida();
        } else {
          $this->message = $cardapio->getMessage();
          $this->error = true;
        }
      } catch (Exception $e) {
        $this->message = $e->getMessage();
        $this->error = true;
      }
    }
  }
  public function getMessage()
  {
    if (is_string($this->error)) {
      return $this->message;
    } else {
      $msg = new Template("view/msg.html");
      if ($this->error) {
        $msg->set("cor", "danger");
      } else {
        $msg->set("cor", "success");
      }
      $msg->set("msg", $this->message);
      $msg->set("uri", "?class=Tabela");
      return $msg->saida();
    }
  }
  public function __destruct()
  {
    Transaction::close();
  }
}