<?php

require "./Bibliotecas/PHPMailer/Exception.php";
require "./Bibliotecas/PHPMailer/OAuth.php";
require "./Bibliotecas/PHPMailer/PHPMailer.php";
require "./Bibliotecas/PHPMailer/POP3.php";
require "./Bibliotecas/PHPMailer/SMTP.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mensagem {
    private $para     = null;
    private $assunto  = null;
    private $mensagem = null;

    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    public function mensagemValida() {
        if(empty($this->para) || empty($this->assunto) || empty($this->mensagem)) {
            return false;
        }
        return true;
    }

}

$mensagem = new Mensagem();

$mensagem->__set('para',     $_POST['para']);
$mensagem->__set('assunto',  $_POST['assunto']);
$mensagem->__set('mensagem', $_POST['mensagem']);

if(!$mensagem->mensagemValida()) {
    echo 'A mensagem não é valida.';
    die();
} 

$mail = new PHPMailer(true);

try {
    //Configurações do servidor 
    //$mail->SMTPDebug  = SMTP::DEBUG_SERVER;                   //Habilitar saída de depuração detalhada
    $mail->isSMTP();                                            //Enviar usando SMTP
    $mail->Host       = 'smtp.gmail.com';                       //Defina o servidor SMTP para enviar
    $mail->SMTPAuth   = true;                                   //Habilitar autenticação SMTP
    $mail->Username   = 'fabiofrare.jb@gmail.com';              //Nome de usuário SMTP 
    $mail->Password   = 'minha senha';                          //Senha SMTP 
    //$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;          //Habilitar criptografia TLS implícita
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;                                    //Porta TCP para conectar; use 587 se você tiver definido `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Destinatários
    $mail->setFrom('fabiofrare.jb@gmail.com', 'Fábio');
    $mail->addAddress($mensagem->__get('para'));                  //Adicionar um destinatário 
    //$mail->addAddress('ellen@example.com');                     //O nome é opcional
    //$mail->addReplyTo('info@example.com', 'Information');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    //Anexos
    //$mail->addAttachment('/var/tmp/file.tar.gz');               //Adicionar anexos
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');          //Nome opcional

    //Conteúdo 
    $mail->isHTML(true);                                          //Define o formato de e-mail para HTML
    $mail->Subject = $mensagem->__get('assunto');
    $mail->Body    = $mensagem->__get('mensagem');
    //$mail->AltBody = $mensagem->__get('mensagem');

    $mail->send();
    echo 'E-mail enviado com sucesso.';
} catch (Exception $e) {
    echo "Não foi possível enviar este e-mail. Detalhes do erro: {$mail->ErrorInfo}";
}

?>