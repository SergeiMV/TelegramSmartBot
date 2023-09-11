<?php

namespace TelegramSmartBot\Tools\TelegramCore\Commands;

use SergiX44\Nutgram\Nutgram;

abstract class AdminCommandWrapper extends CommandWrapper
{
    public function handle(Nutgram $bot, $param = null): void
    {
        try {
            $this->user = $this->handleUser($bot->chat());
            if (!$this->isAdmin($this->user)) {
                throw new \Exception('Not an admin');
            }
        } catch (\Exception $e) {
            $this->getLogger()->error($e->getMessage());
            $bot->sendMessage('Something went wrong');
        }
        
        $this->createMessage($bot, $param);
    }
}
