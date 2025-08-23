<?php 

namespace App\Support;

use Symfony\Component\HttpFoundation\Session\Session;

/**
 * COFFEE CODE
 * @author Jenilson D. Da C. Lucas
 * @package App\Support
 */
class Message {

    /** @var string */
    private $text;

    /** @var string */
    private $type;

    /** @var string */
    private $before;

    /** @var string*/
    private $after;

    /**
     * @return string
     */
    public function  __toString()
    {
        return $this->render();
    }

    /** @return string */
    public function getText(): ?string
    {
        return $this->before . $this->text . $this->after;
    }

    /** @return string */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $text
     * @return Message
     */
    public function before(string $text): Message
    {
        $this->before = $text;
        return $this;
    }

    /**
     * @param string $text
     * @return Message
     */
    public function after(string $text): Message
    {
        $this->after = $text;
        return $this;
    }

    public function info(string $text): Message
    {
         $this->type = "info icon-info";
         $this->text = $text;
         return $this;
    }

    public function success(string $text): Message
    {
         $this->type = "success icon-check-square-o";
         $this->text = $text;
         return $this;
    }

    public function warning(string $text): Message
    {
         $this->type = "warning icon-warning";
         $this->text = $text;
         return $this;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        return "<div class='message {$this->getType()}'> {$this->getText()} </div>";
    }

    /**
     * Set flash Session Key
     */
    public function flash(): void
    {
        (new Session())->set("flash", $this);
    }
}