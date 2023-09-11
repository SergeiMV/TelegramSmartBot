<?php

namespace TelegramSmartBot\Modules\MainMenu;

use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use TelegramSmartBot\Modules\Admin\AdminMenuCommand;
use TelegramSmartBot\Tools\TelegramCore\Commands\CommandWrapper;

class MainMenuCommand extends CommandWrapper
{
    protected string $command = 'start';
    protected ?string $description = 'Main menu';


    public function getCommand() : string
    {
        return $this->command;
    }


    public function getCommandName() : string
    {
        return $this->description;
    }


    protected function getCommandText() : string
    {
        return 'Welcome to main menu.';
    }


    protected function getParentCommand() : string
    {
        return $this->getCommand();
    }


    protected function getReplayMarkup(Nutgram $bot) : array
    {

        $reply = [
            InlineKeyboardButton::make('One', callback_data: 'one'),
        ];

        if ($this->isAdmin($this->handleUser($bot->chat()))) {
            $reply[] = InlineKeyboardButton::make((new AdminMenuCommand())->getCommandName(), callback_data: (new AdminMenuCommand())->getCommand());
        }

        return $reply;
    }
    
}