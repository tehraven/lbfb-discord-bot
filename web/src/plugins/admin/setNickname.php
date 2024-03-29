<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2016 Robert Sardinia
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

/**
 * @property  message
 */
class setNickname
{
    public $config;
    public $discord;
    public $logger;
    public $message;

    /**
     * @param $config
     * @param $discord
     * @param $logger
     */
    public function init($config, $discord, $logger)
    {
        $this->config = $config;
        $this->discord = $discord;
        $this->logger = $logger;
    }

    /**
     * @param $msgData
     * @param $message
     * @return null
     */
    public function onMessage($msgData, $message)
    {
        $this->message = $message;

        $message = $msgData['message']['message'];

        $data = command($message, $this->information()['trigger'], $this->config['bot']['trigger']);
        if (isset($data['trigger'])) {

            //Admin Check
            $botID = $this->discord->id;
            $userID = $msgData['message']['fromID'];
            $adminRoles = $this->config['bot']['adminRoles'];
            $adminRoles = array_map('strtolower', $adminRoles);
            $id = $this->config['bot']['guild'];
            $guild = $this->discord->guilds->get('id', $id);
            $member = $guild->members->get('id', $userID);
            $roles = $member->roles;
            foreach ($roles as $role) {
                if (in_array(strtolower($role->name), $adminRoles, true)) {
                    $member = $guild->members->get('id', $botID);
                    $nick = (string) $data['messageString'];
                    $member->setNickname($nick);

                    $msg = "Bot nickname changed to **{$nick}** by {$msgData['message']['from']}";
                    $this->logger->addInfo("setNickname: Bot nickname changed to {$nick} by {$msgData['message']['from']}");
                    $this->message->reply($msg);
                    return null;
                }
            }
            $this->logger->addInfo("setNickname: {$msgData['message']['from']} attempted to change the bot's nickname.");
            $msg = ':bangbang: You do not have the necessary roles to issue this command :bangbang:';
            $this->message->reply($msg);
            return null;
        }
        return null;
    }

    /**
     * @return array
     */
    public function information()
    {
        return array(
            'name' => 'nickname',
            'trigger' => array($this->config['bot']['trigger'] . 'nickname'),
            'information' => 'Changes the bots nickname **(Admin Role Required)**'
        );
    }

}
