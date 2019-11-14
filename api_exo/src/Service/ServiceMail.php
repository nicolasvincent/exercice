<?php


namespace App\Service;


class ServiceMail extends \Swift_Mailer
{

    public function SendMail($data2,$client)
    {
        $message = (new \Swift_Message('Email'))
            ->setFrom('nicolastestmailexercice@gmail.com')
            ->setTo($data2->getEmail())
            ->setBody(

                $client->getName().$client->getSurname().$client->getEmail().$client->getMessage()
            );
        $mailer=$this->send($message);
    }
}