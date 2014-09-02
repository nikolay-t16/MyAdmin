<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MailSender
 *
 * @author user
 */
class MailSender {

    /**
     *
     * @var MailSender
     */
    protected static $Instanse;

    /**
     *
     * @return MailSender
     */
    public static function I() {
        if (!self::$Instanse)
            self::$Instanse = new MailSender();
        return self::$Instanse;
    }

    protected $ViewMainPath = '/component/main/view/email/';

    protected function __construct() {

    }

    protected $SmptSetings = array(
        "host" => "smtp.yandex.ru",
        "debug" => false,
        "auth" => true,
        "port" => 25
    );
    protected $MailSetNoReplay = array(
        "username" => "info@vse-o-vseh.com",
        "name" => "info@vse-o-vseh.com",
        "password" => "123321",
        "addreply" => "info@vse-o-vseh.com",
        "replyto" => "info@vse-o-vseh.com"
    );

    public function SendActivateMail($to, $hash) {
        $subject = "Регистрация на сайте " . Config::SITE_DOMAIN_NAME;
        $shablon = ROOT_PATH . '/component/index/view/email/activate.phtml';
        $shablon_param = array('email' => $to, 'hash' => $hash);
        $body = view::template($shablon, $shablon_param);

        $this->SendMail($to, $subject, $body, $this->MailSetNoReplay, $this->SmptSetings);
    }

    public function SendRestorePasswordMail($email, $hash) {
        $subject = "Востановление пароля";
        $shablon = ROOT_PATH . '/component/public_user/view/email/restore_password.phtml';
        $shablon_param = array('email' => $email, 'hash' => $hash);
        $body = view::template($shablon, $shablon_param);

        $this->SendMail($email, $subject, $body, $this->MailSetNoReplay, $this->SmptSetings);
    }

    public function SendMail($email_to, $subject, $body, $fromSettings='', $smptSetings='',$files=array()) {
        if (!$fromSettings)
            $fromSettings = $this->MailSetNoReplay;
        if (!$smptSetings)
            $smptSetings = $this->SmptSetings;

        $mail = new PHPMailer(true);
        $mail->IsSMTP();
        try {

            $mail->Host = $smptSetings['host'];
            $mail->SMTPDebug = $smptSetings['debug'];
            $mail->SMTPAuth = $smptSetings['auth'];
            $mail->Host = $smptSetings['host'];
            $mail->Port = $smptSetings['port'];

            $mail->Username = $fromSettings['username'];
            $mail->Password = $fromSettings['password'];
            $mail->SetLanguage('ru');
            $mail->SetLanguage('ru');
            $mail->AddReplyTo($fromSettings['addreply'], $fromSettings['name']);
            $mail->SetFrom($fromSettings['replyto'], $fromSettings['name']);

            if (is_array($email_to))
                foreach ($email_to as $email) {
                    $mail->AddAddress(trim($email));
                }
            if (is_string($email_to))
                $mail->AddAddress(trim($email_to));

						if(is_array($files)&&$files)
							foreach ($files as $f){
							@$mail->AddAttachment($f);
							}
            $mail->Subject = htmlspecialchars($subject);
            $mail->MsgHTML($body);
            $mail->Send();
        } catch (phpmailerException $e) {
            echo 1;
            echo $e->errorMessage();
        } catch (Exception $e) {
            echo 2;
            echo $e->getMessage();
        }
    }

    public function smtp_male_action($param = array(), &$vParam = array(), &$vShab = array()) {
        $model_param = array(
            'tables' => array(
                'main' => array(
                    'table' => 'client__spam_mail',
                    'ident' => 'id'))
        );
        $model = new model($model_param);
        $mails = $model->GetItems("sent=0 and id_mailer='{$param['id_mailer']}'", 4);
        $mailer_inf = $this->GetMailerModel()->GetItem($param['id_mailer']);
        if (!$mails) {
            $count = $model->GetCount();
            $date = WithDate::GetDateTime();
            $this->GetMailerModel()->add(array(
                'id' => $param['id_mailer'],
                'date_last_sent' => $date,
                'count' => $count)
            );
            echo 0;
            die;
        } else {
            foreach ($mails as $email) {
                $email['sent'] = 2;
                $model->add($email);
            }

            $__smtp = array(
                "host" => "smtp.spaceweb.ru",
                "debug" => 2,
                "auth" => true,
                "port" => 2525,
                "username" => "niiapsp.ru+seminari",
                "name" => "НИИ АПСП",
                "password" => "nikolla",
                "addreply" => "seminari@niiapsp.ru",
                "replyto" => "seminari@niiapsp.ru"
            );

            $mail = new PHPMailer(true);
            $mail->IsSMTP();
            try {
                $mail->Host = $__smtp['host'];
                $mail->SMTPDebug = $__smtp['debug'];
                $mail->SMTPAuth = $__smtp['auth'];
                $mail->Host = $__smtp['host'];
                $mail->Port = $__smtp['port'];
                $mail->Username = $__smtp['username'];
                $mail->Password = $__smtp['password'];
                $mail->SetLanguage('ru');
                $mail->SetLanguage('ru');
                $mail->AddReplyTo($__smtp['addreply'], $__smtp['name']);
                foreach ($mails as &$email) {
                    if (PHPMailer::ValidateAddress(trim($email['email']))) {

                        $mail->AddAddress(trim($email['email']));
                        $email['sent'] = 1;
                        $email['date'] = date("Y-m-d h:i:s");
                        $model->add($email);
                    } else {
                        $email['log'] = 'invalid adress';
                        $email['sent'] = 2;
                        $model->add($email);
                    }
                }
                $mail->SetFrom($__smtp['addreply'], $__smtp['name']);
                $mail->AddReplyTo($__smtp['addreply'], $__smtp['name']);
                $mail->Subject = htmlspecialchars($mailer_inf['subject']);
                $mail->MsgHTML($mailer_inf['text']);
                //if($attach)  $mail->AddAttachment($attach);
                $mail->Send();

                $mails[0]['log'] = "Message sent Ok!</p>\n";
            } catch (phpmailerException $e) {
                $mails[0]['log'] = $e->errorMessage();
            } catch (Exception $e) {
                $mails[0]['log'] = $e->getMessage();
            }
            $model->add($mails[0]);




            echo 1;
        }
        die;
    }

}