<?php 
    header("Content-Type: application/json");
    header("Access-Control-Allow-Origin: *");

    //validar datos
    validate(['name','phone','email','subject','message']);

    //armar mensaje
    $message = getMessage(
        array(
            array('name','Nombre'),
            array('phone','Teléfono'),
            array('email','Correo'),
            array('message','Mensaje'),
            array('subject','Asunto')
        ));

    //enviar mensaje
    enviarCorreo('germang08@hotmail.com',$_POST['subject'],$message,$_POST['email'],$_POST['name']);

    //mensaje de retorno exitoso
    //response(200,'success','Mensaje enviado');
    header("Location: ../?message=Mensaje enviado&send=yes#section-contact");
    exit();


    /**
     * Se envia un mail
     * $to: correo al que se envia el mail
     * $asunto: asunto que se muestra en el correo
     * $mensaje: cuerpo del mensaje en formato HTML
     * $from: correo desde el que se envia el mail
     * $from_name: nombre representativo del cual se envia el mail
     */
    function enviarCorreo($to,$asunto,$mensaje,$from,$from_name=NULL){


        //incluir galerias
        include_once 'PHPMailer/PHPMailer.php';
        include_once 'PHPMailer/Exception.php';
        include_once 'PHPMailer/OAuth.php';
        include_once 'PHPMailer/POP3.php';
        include_once 'PHPMailer/SMTP.php';

        //crear instancia
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        //tipo de envio
        $mail->isSMTP();
        //tipo de smtp
        $mail->SMTPDebug = false;
        //host mail de neolo
        $mail->Host = 'mail.xeronweb.com';
        // puerto para ssl/tls
        $mail->Port = 465;
        //seguridad
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "ssl";
        //usuario y pass
        $mail->Username = 'gergomez@xeronweb.com';
        $mail->Password = 'Gomez1520!';
        //quien envia
        if($from_name!=NULL){
            $mail->setFrom($from, $from_name);
            $mail->addReplyTo($from,$from_name);
        }else{
            $mail->setFrom($from);
            $mail->addReplyTo($from);		
        }
        //a quien manda
        $mail->addAddress($to);
    
        //asunto y mensaje
        $mail->Subject = $asunto;
        $mail->isHTML(true);
        $mail->Body = $mensaje;
        $mail->CharSet = 'UTF-8';
    
        //enviar correo
        if(!$mail->send()){
            response(481,'error',$mail->ErrorInfo);
        }
    }


    /**
     * Recibe un array que verifica que datos faltan
     */
    function validate($data){
        foreach($data as $d){
            if(!isset($_POST[$d]) || $_POST[$d]=='undefined' || $_POST[$d]==''){
                response(480,'error','Ingrese todos los datos requeridos. '.$d);
            }
        }
    }

    /**
     * Retorna un mensaje de error
     */
    function response($code,$status,$message){    
        header('../#section-contact?message='.$message );
        //echo '<meta http-equiv="refresh" content="0; url=../#section-contact?message='.$message.'" />';
    }

    /**
     * Retorna un mensaje formateada
     */
    function getMessage($data){
        $message = '';
        foreach($data as $d){
            $value = $_POST[$d[0]];

            $message = $message.$d[1].': '.$value.'<br>'; 
        }
        return $message;
    }


?>
