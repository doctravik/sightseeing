<?php

namespace App\Flash;

class Flash
{
    /**
     * Session key.
     *
     * @var string
     */
    protected $name;

    /**
     * Create new instance of Flash.
     *
     * @param string $name
     */
    public function __construct($name = 'flash')
    {
        $this->name = $name;
    }

    /**
     * Save message with session.
     *
     * @param  Message $message
     * @return void
     */
    public function put(Message $message)
    {
        session($this->name, $message);
    }

    /**
     * If session has value.
     *
     * @return boolean
     */
    public function exists()
    {
        return session()->has($this->name);
    }

    /**
     * Get body of the message.
     *
     * @return string
     */
    public function message()
    {
        return session($this->name)->body();
    }

    /**
     * Get type of the message.
     *
     * @return string
     */
    public function type()
    {
        return session($this->name)->type();
    }
}
