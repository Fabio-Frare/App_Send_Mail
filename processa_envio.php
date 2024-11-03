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
        public $status    = array('codigo_status' => null, 'descricao_status' => '' );
        

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
        header('Location: index.php');
    } 

    $mail = new PHPMailer(true);

    try {
        //Configurações do servidor 
        //$mail->SMTPDebug  = SMTP::DEBUG_SERVER;                   //Habilitar saída de depuração detalhada
        $mail->isSMTP();                                            //Enviar usando SMTP
        $mail->Host       = 'smtp.gmail.com';                       //Defina o servidor SMTP para enviar
        $mail->SMTPAuth   = true;                                   //Habilitar autenticação SMTP
        $mail->Username   = 'meu email';                            //Nome de usuário SMTP 
        $mail->Password   = 'senha do meu email';                   //Senha SMTP 
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
        $mensagem->status['codigo_status']    = 1;
        $mensagem->status['descricao_status'] = 'E-mail enviado com sucesso.';

    } catch (Exception $e) {
        $mensagem->status['codigo_status']    = 2;
        $mensagem->status['descricao_status'] = 'Não foi possível enviar este e-mail. </br> Detalhes do erro: '. $mail->ErrorInfo;
    }

?>

<html>
    <head>
        <meta charset="utf-8" />
    	<title>App Mail Send</title>
    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    </head>

    <body>
        <div class="container">

            <div class="py-3 text-center">
				<img class="d-block mx-auto mb-2" src="imagens/logo.png" alt="" width="72" height="72">
				<h2>Send Mail</h2>
				<p class="lead">Seu app de envio de e-mails particular!</p>
			</div>

            <div class="row"> 
                <div class="col-md-12">

                   <?php if($mensagem->status['codigo_status'] == 1) { ?>

                    <div class="container text-center">
                        <h1 class="display-4 text-success">Sucesso!</h1>
                        <p><?= $mensagem->status['descricao_status'] ?></p>
                        <a href="index.php" class="btn btn-success btn-lg mt-5 text-white ">Voltar</a>
                    </div>

                   <?php } ?>

                   <?php if($mensagem->status['codigo_status'] == 2) { ?>

                    <div class="container text-center">
                        <h1 class="display-4 text-danger">Ops!</h1>
                        <p><?= $mensagem->status['descricao_status'] ?></p>
                        <a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
                    </div>

                    <?php } ?>  

                </div>
            </div>
        </div>

    </body>
</html>