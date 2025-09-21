<?php

class Emails {

    var $config;

    public function __construct() {
        $CI = & get_instance();
        $CI->load->library("email");

        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'smtp.ipage.com';
        $config['smtp_port'] = 25;
        $config['charset'] = 'utf-8';
        $config['mailtype'] = 'html';
        $config['smtp_user'] = 'andres@desarrollolibre.net';
        $config['smtp_pass'] = 'PepeLePew7';

        $CI->email->initialize($config);
    }

    public function recover_account($link, $email) {

        $CI = & get_instance();
        $subject = 'Recuperación de cuenta';
        $data['link'] = $link;
        $html = $CI->load->view('email/recover_account', $data, TRUE);


        $this->send_email($email, $subject, $html);

        // echo $CI->email->print_debugger();
    }

    private function send_email($email, $subject, $html) {
        $CI = & get_instance();
        $CI->email->from('andres@desarrollolibre.net', 'Andrés');
        $CI->email->to($email);

        $CI->email->subject($subject);
        $CI->email->message($html);

        $CI->email->send();
    }

}
