<?php
/**
 * https://meetanshi.com/blog/add-attachments-with-email-in-magento-2-3-x/
 */
namespace Prymag\OrdersExporter\Mail;

use Zend\Mime\Mime;
use Zend\Mime\PartFactory;
use Zend\Mail\MessageFactory as MailMessageFactory;
use Zend\Mime\MessageFactory as MimeMessageFactory;
use Magento\Framework\Mail\MailMessageInterface;

class Message implements MailMessageInterface
{
    protected $partFactory;
    protected $mimeMessageFactory;
    private $zendMessage;
    protected $parts = [];

    public function __construct(
        PartFactory $partFactory,
        MimeMessageFactory $mimeMessageFactory,
        $charset = 'utf-8'
    )
    {
        $this->partFactory = $partFactory;
        $this->mimeMessageFactory = $mimeMessageFactory;
        $this->zendMessage = MailMessageFactory::getInstance();
        $this->zendMessage->setEncoding($charset);
    }

    public function setBodyText($content)
    {
        $textPart = $this->partFactory->create();
        $textPart->setContent($content)
            ->setType(Mime::TYPE_TEXT)
            ->setCharset($this->zendMessage->getEncoding());
        $this->parts[] = $textPart;
        return $this;
    }

    public function setBodyHtml($content)
    {
        $htmlPart = $this->partFactory->create();
        $htmlPart->setContent($content)
            ->setType(Mime::TYPE_HTML)
            ->setCharset($this->zendMessage->getEncoding());
        $this->parts[] = $htmlPart;
        return $this;
    }

    public function setBodyAttachment($content, $fileName)
    {
        $fileType = Mime::TYPE_OCTETSTREAM;

        $attachmentPart = $this->partFactory->create();
        $attachmentPart->setContent($content)
            ->setType($fileType)
            ->setFileName($fileName)
            ->setDisposition(Mime::DISPOSITION_ATTACHMENT)
            ->setEncoding(Mime::ENCODING_BASE64);
        $this->parts[] = $attachmentPart;
        return $this;
    }

    public function setPartsToBody()
    {
        $mimeMessage = $this->mimeMessageFactory->create();
        $mimeMessage->setParts($this->parts);
        $this->zendMessage->setBody($mimeMessage);
        return $this;
    }

    public function setBody($body)
    {
        return $this;
    }

    public function setSubject($subject)
    {
        $this->zendMessage->setSubject($subject);
        return $this;
    }

    public function getSubject()
    {
        return $this->zendMessage->getSubject();
    }

    public function getBody()
    {
        return $this->zendMessage->getBody();
    }

    public function setFrom($fromAddress)
    {
        $this->zendMessage->setFrom($fromAddress);
        return $this;
    }

    public function addTo($toAddress)
    {
        $this->zendMessage->addTo($toAddress);
        return $this;
    }

    public function addCc($ccAddress)
    {
        $this->zendMessage->addCc($ccAddress);
        return $this;
    }

    public function addBcc($bccAddress)
    {
        $this->zendMessage->addBcc($bccAddress);
        return $this;
    }

    public function setReplyTo($replyToAddress)
    {
        $this->zendMessage->setReplyTo($replyToAddress);
        return $this;
    }

    public function getRawMessage()
    {
        return $this->zendMessage->toString();
    }

    public function setMessageType($type)
    {
        return $this;
    }
}