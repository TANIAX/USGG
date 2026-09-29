<?php

namespace App\Helpers;

/**
 * Sending of the e-mails of the site (HTML + text version).
 * The SMTP server and the sender are configured in the .env file ("email.*" keys).
 *
 * @author Guillaume Cornez
 */
class MailHelper
{
    /**
     * Sends an e-mail built from a view of app/Views/emails.
     *
     * @return bool false if the e-mail could not be sent (the error is logged)
     */
    public static function send(string $to, string $subject, string $view, array $data = [], ?string $replyTo = null)
    {
        $email = service('email');
        $config = config('Email');
        $email->setFrom($config->fromEmail ?: 'noreply@gsgosselies.be', $config->fromName ?: 'Guides et Scouts de Gosselies');
        $email->setTo($to);
        if ($replyTo)
            $email->setReplyTo($replyTo);
        $email->setSubject($subject);

        $html = view($view, $data + ['subject' => $subject]);
        $email->setMailType('html');
        $email->setMessage($html);
        //Text version for the mail clients that do not display HTML
        $text = preg_replace('#<head>.*?</head>#s', '', $html);
        $text = html_entity_decode(strip_tags(str_replace(['<br>', '</p>', '</tr>'], "\n", $text)), ENT_QUOTES, 'UTF-8');
        $email->setAltMessage(trim(preg_replace("/\n\s*\n+/", "\n\n", $text)));

        if ($email->send(false))
            return true;

        log_message('error', 'E-mail "{subject}" not sent to {to}: {debug}', ['subject' => $subject, 'to' => $to, 'debug' => $email->printDebugger(['headers'])]);
        return false;
    }
}
