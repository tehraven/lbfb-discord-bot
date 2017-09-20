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
 * Class time
 * @property  message
 */
class time
{
    public $config;
    public $discord;
    public $logger;
    public $message;
    private $excludeChannel;
    private $triggers;

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
        $this->excludeChannel = $this->config['bot']['restrictedChannels'];
        $this->triggers[] = $this->config['bot']['trigger'] . 'time';
        $this->triggers[] = $this->config['bot']['trigger'] . 'Time';
    }

    /**
     * @param $msgData
     * @param $message
     * @return null
     */
    public function onMessage($msgData, $message)
    {
        $channelID = (int) $msgData['message']['channelID'];

        if (in_array($channelID, $this->excludeChannel, true))
        {
            return null;
        }

        $this->message = $message;
        $user = $msgData['message']['from'];

        $message = $msgData['message']['message'];

        $data = command($message, $this->information()['trigger'], $this->config['bot']['trigger']);
        if (isset($data['trigger'])) {
            $date = date('d-F-Y');
            $fullDate = date('Y-m-d H:i:s');
            $datetime = new DateTime($fullDate);
            $est = $datetime->setTimezone(new DateTimeZone('America/New_York'));
            $est = $est->format('H:i:s');
            $pst = $datetime->setTimezone(new DateTimeZone('America/Los_Angeles'));
            $pst = $pst->format('H:i:s');
            $utc = $datetime->setTimezone(new DateTimeZone('UTC'));
            $utc = $utc->format('H:i:s');
            $cet = $datetime->setTimezone(new DateTimeZone('Europe/Copenhagen'));
            $cet = $cet->format('H:i:s');
            $msk = $datetime->setTimezone(new DateTimeZone('Europe/Moscow'));
            $msk = $msk->format('H:i:s');
            $aus = $datetime->setTimezone(new DateTimeZone('Australia/Sydney'));
            $aus = $aus->format('H:i:s');

            $this->logger->addInfo("Time: Sending time info to {$user}");
            $this->message->reply("**EVE Time:** {$utc} -- **EVE Date:** {$date} -- **PST/Los Angeles:** {$pst} -- **EST/New York:** {$est} -- **CET/Copenhagen:** {$cet} -- **MSK/Moscow:** {$msk} -- **AEST/Sydney:** {$aus}");
        }
    }

    /**
     * @return array
     */
    public function information()
    {
        return array(
            'name' => 'time',
            'trigger' => $this->triggers,
            'information' => 'This shows the time for various timezones compared to EVE Time. To use simply type <!time>'
        );
    }
}