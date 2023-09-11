<?php

namespace TelegramSmartBot\Tools\TelegramCore\Models;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(type: 'integer')]
    private int $chatId;

    #[ORM\Column(type: 'string')]
    private string $userName;

    #[ORM\Column(type: 'string')]
    private string $firstName;

    #[ORM\Column(type: 'string')]
    private string $lastName;

    #[ORM\Column(type: 'string')]
    private string $chatType;

    #[ORM\Column(type: 'datetime')]
    private DateTime $createdAt;


    public function getChatId() : int
    {
        return $this->chatId;
    }


    public function setChatId(int $chatId) 
    {
        $this->chatId = $chatId;
    }


    public function getUserName() : string
    {
        return $this->userName;
    }


    public function setUserName(string $userName) 
    {
        $this->userName = $userName;
    }


    public function getFirstName() : string
    {
        return $this->firstName;
    }


    public function setFirstName(string $firstName) 
    {
        $this->firstName = $firstName;
    }


    public function getLastName() : string
    {
        return $this->lastName;
    }


    public function setLastName(string $lastName) 
    {
        $this->lastName = $lastName;
    }


    public function getChatType() : string
    {
        return $this->chatType;
    }


    public function setChatType(string $chatType) 
    {
        $this->chatType = $chatType;
    }


    public function getCreatedAt() : DateTime
    {
        return $this->createdAt;
    }


    public function setCreatedAt()
    {
        $this->createdAt = new DateTime();
    }

}