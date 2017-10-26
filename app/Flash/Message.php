<?php

namespace App\Flash;

class Message
{
    /**
     * Content of the message.
     *
     * @var string
     */
    protected $body;

    /**
     * Type of the message.
     *
     * @var string
     */
    protected $type;

    /**
     * Create new instance of Message.
     *
     * @param string $body
     * @param string $type
     */
    public function __construct($body, $type)
    {
        $this->body = $body;
        $this->type = $type;
    }

    /**
     * Getter for the body.
     *
     * @return string
     */
    public function body()
    {
        return $this->body;
    }

    /**
     * Getter for the type.
     *
     * @return string
     */
    public function type()
    {
        return $this->type;
    }
}
