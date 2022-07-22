<?php


// Install with `composer require nette/mail`
require_once('vendor/autoload.php');

$smtp = new Nette\Mail\SmtpMailer([
    'host' => 'smtp.mailosaur.net',
    'port' => 587,
    'username' => 'qblxgndf@mailosaur.net',
    'password' => 'PCeaAMH1zFnK3hge',
    'secure' => 'starttls',
]);

$message = new Nette\Mail\Message;
$message->setFrom('Our Company <from@example.com>')
    ->addTo('Test User <to@example.com>')
    ->setSubject('A test email')
    ->setHtmlBody('<p>Hello world.</p>');

$smtp->send($message);
