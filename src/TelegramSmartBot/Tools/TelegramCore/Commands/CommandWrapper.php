<?php

namespace TelegramSmartBot\Tools\TelegramCore\Commands;

use Psr\Log\LoggerInterface;
use SergiX44\Nutgram\Handlers\Type\Command;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use TelegramSmartBot\Modules\MainMenu\MainMenuCommand;
use TelegramSmartBot\Tools\Database\DatabaseFactory;
use TelegramSmartBot\Tools\Logger\LoggerFactory;
use TelegramSmartBot\Tools\TelegramCore\Models\User;
use SergiX44\Nutgram\Telegram\Types\Chat\Chat;


abstract class CommandWrapper extends Command
{
    protected static ?LoggerInterface $logger = null;

    protected User $user;

    protected function getLogger() : LoggerInterface
    {
        return $this->logger ?? (new LoggerFactory())->getLogger('user_commands');
    }

    abstract public function getCommand() : string;

    abstract public function getCommandName() : string;

    abstract protected function getCommandText() : string;

    abstract protected function getParentCommand() : string;

    abstract protected function getReplayMarkup(Nutgram $bot) : array;

    public function haveParams() : bool
    {
        return false;
    }

    protected function getMenuBackMarkup() : array
    {
        return [
            InlineKeyboardButton::make((new MainMenuCommand())->getCommandName(), callback_data: (new MainMenuCommand())->getCommand()),
            InlineKeyboardButton::make('Back', callback_data: $this->getParentCommand()),
        ];
    }

    protected function isAdmin(User $user) : bool
    {
        return 
            $user->getChatId() === (int) $_ENV['TELEGRAM_ADMIN_ID']
            && $user->getUserName() === $_ENV['TELEGRAM_ADMIN_USERNAME'];
    }


    public function handle(Nutgram $bot, $param = null): void
    {
        try {
            $this->user = $this->handleUser($bot->chat());
        } catch (\Exception $e) {
            $this->getLogger()->error($e->getMessage());
            $bot->sendMessage('Something went wrong');
        }
        
        $this->createMessage($bot, $param); 
    }


    protected function createMessage(Nutgram $bot, $param)
    {
        $keyboardMarkup = InlineKeyboardMarkup::make();
        foreach ($this->getReplayMarkup($bot) as $markUp) {
            $keyboardMarkup->addRow($markUp);
        }
        $keyboardMarkup->addRow(...$this->getMenuBackMarkup());

        $bot->sendMessage(
            text: $this->getDescription() . "\n" .  $this->getCommandText(),
            reply_markup: $keyboardMarkup
        );
    }


    /**
     * @throws \Throwable
     */
    protected function handleUser(Chat $chat) : User
    {
        $chatId = $chat->id;

        $chatUsername = $chat->username ?? '';

        $chatType = $chat->type->name;

        if (!$chat || !$chat->isPrivate()) {
            throw new \Exception("Chat is not private. ChatId: {$chatId}; ChatName: {$chatUsername}; ChatType: {$chatType}.");
        }

        $entityManager = DatabaseFactory::getEntityManager();

        $user = $entityManager->getRepository('TelegramSmartBot\Tools\TelegramCore\Models\User')->findOneBy([
            'chatId' => $chatId,
        ]);

        if ($user){
            return $user;
        }

        $user = new User();

        $user->setChatId($chatId);
        $user->setUserName($chatUsername);
        $user->setChatType($chatType);
        $user->setFirstName($chat->first_name ?? '');
        $user->setLastName($chat->last_name ?? '');
        $user->setCreatedAt();

        $entityManager->persist($user);
        $entityManager->flush();

        $this->getLogger()->info("User added. Id: {$chatId}; Username {$chatUsername}; Name {$user->getFirstName()}");

        return $user;
    }

}
