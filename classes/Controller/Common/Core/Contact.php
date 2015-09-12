<?php

/**
 * Created by IntelliJ IDEA.
 * User: mauricio
 * Date: 6/9/2015
 * Time: 8:38 AM
 */
class Controller_Common_Core_Contact extends Controller_Website
{

    public function action_form()
    {
        $main = 'contact/form';
        $this->page_title = 'Contact';


        View::bind_global('main', $main);
    }


    public function action_ajax_validate()
    {
        $this->output = array(
            'posted' => $_POST,
        );
        $error = false;


        $mail = new PHPMailer;
        $mail->From = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $mail->FromName = filter_var($_POST['name'], FILTER_SANITIZE_EMAIL);

        //To address and name
        $mail->addAddress("mauricio@portnumber53.com", "Mauricio S Otta");

        //Send HTML or Plain Text email
        $mail->isHTML(true);

        $mail->Subject = filter_var($_POST['subject'], FILTER_SANITIZE_EMAIL);
        $mail->Body = filter_var($_POST['message'], FILTER_SANITIZE_EMAIL);
        $mail->AltBody = filter_var($_POST['message'], FILTER_SANITIZE_EMAIL);
        if (!$mail->send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            echo "Message has been sent successfully";
        }


        $this->output['error'] = $error;
    }
}
