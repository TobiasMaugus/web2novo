<?php
class Inicio
{
  private $message = "";
  public function controller()
  {
    $inicio = new Template('restrict/view/inicio.html');
    $inicio->set('inicio', 'Área restrita!!');
    $this->message = $inicio->saida();
  }
  public function getMessage()
  {
    return $this->message;
  }
}