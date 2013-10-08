<?php


namespace Terramar\Bundle\SalesBundle\Helper;

use Symfony\Component\Templating\EngineInterface;
use Swift_Mailer;

class EmailHelper
{
    /**
     * @var \Symfony\Component\Templating\EngineInterface
     */
    protected $templating;

    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var string
     */
    protected $defaultSender;

    /**
     * @param \Symfony\Component\Templating\EngineInterface $templating
     * @param \Swift_Mailer $mailer
     * @param string $defaultSender
     */
    public function __construct(EngineInterface $templating, Swift_Mailer $mailer, $defaultSender)
    {
        $this->templating = $templating;
        $this->mailer = $mailer;
        $this->defaultSender = $defaultSender;
    }

    public function sendMessage(\Swift_Message $message)
    {
        $this->mailer->send($message);
    }

    public function createMessageFromTemplate($template, $params, $subject, $recipient, $sender = null)
    {
        $body = $this->templating->render($template, $params);

        if(!$sender) {
            $sender = $this->defaultSender;
        }

        $message = new \Swift_Message();
        $message->setFrom($sender)
            ->setReplyTo($sender)
            ->setTo($recipient)
            ->setSubject($subject)
            ->setBody($body, 'text/html');
        return $message;
    }

    public function createAndSendMessageFromTemplate($template, $params, $subject, $recipient, $sender = null)
    {
        $message = $this->createMessageFromTemplate($template, $params, $subject, $recipient, $sender);

        $this->sendMessage($message);

        return true;
    }

}
