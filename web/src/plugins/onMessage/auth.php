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
use discord\discord;

/**
 * Class auth
 */
class auth
{
    public $config;
    public $discord;
    public $logger;
    private $excludeChannel;
    private $nameEnforce;
    private $db;
    private $dbUser;
    private $dbPass;
    private $dbName;
    private $ssoUrl;
    private $corpTickers;
    private $authGroups;
    private $standingsBased;
    private $guild;
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
        $this->db = $config['database']['host'];
        $this->dbUser = $config['database']['user'];
        $this->dbPass = $config['database']['pass'];
        $this->dbName = $config['database']['database'];
        $this->corpTickers = $config['plugins']['auth']['corpTickers'];
        $this->nameEnforce = $config['plugins']['auth']['nameEnforce'];
        $this->ssoUrl = $config['plugins']['auth']['url'];
        $this->excludeChannel = $this->config['bot']['restrictedChannels'];
        $this->authGroups = $config['plugins']['auth']['authGroups'];
        $this->standingsBased = $config['plugins']['auth']['standings']['enabled'];
        $this->guild = $config['bot']['guild'];
        $this->triggers[] = $this->config['bot']['trigger'] . 'auth';
        $this->triggers[] = $this->config['bot']['trigger'] . 'Auth';
    }

    /**
     * @param $msgData
     * @param $message
     * @return null
     */
    public function onMessage($msgData, $message)
    {
        $channelID = (int) $msgData['message']['channelID'];

        if (in_array($channelID, $this->excludeChannel, true)) {
            return null;
        }

        $this->message = $message;
        $userID = $msgData['message']['fromID'];
        $userName = $msgData['message']['from'];
        $message = $msgData['message']['message'];
        $guildID = $this->guild;
        $guild = $this->discord->guilds->get('id', $guildID);
        $data = command($message, $this->information()['trigger'], $this->config['bot']['trigger']);
        if (isset($data['trigger'])) {

            // If config is outdated
            if (null === $this->authGroups) {
                $this->message->reply('**Failure:** Please update the bots config to the latest version.');
                return null;
            }

            $code = $data['messageString'];
            $result = selectPending($this->db, $this->dbUser, $this->dbPass, $this->dbName, $code);

            if (strlen($code) < 12) {
                $this->message->reply('Invalid Code, check ' . $this->config['bot']['trigger'] . 'help auth for more info.');
                return null;
            }

            while ($rows = $result->fetch_assoc()) {
                $charID = (int) $rows['characterID'];
                $corpID = (int) $rows['corporationID'];
                $allianceID = (int) $rows['allianceID'];

                //If corp is new store in DB
                $corpInfo = getCorpInfo($corpID);
                if (null === $corpInfo) {
                    $corpDetails = corpDetails($corpID);
                    if (null === $corpDetails) { // Make sure it's always set.
                        $this->message->reply('**Failure:** Unable to auth at this time, ESI is down. Please try again later.');
                        return null;
                    }
                    $corpTicker = $corpDetails['ticker'];
                    $corpName = (string) $corpDetails['corporation_name'];
                    if (null !== $corpTicker) {
                        addCorpInfo($corpID, $corpTicker, $corpName);
                    }
                } else {
                    $corpTicker = $corpInfo['corpTicker'];
                }

                //Add corp ticker to name
                if ($this->corpTickers === 'true') {
                    $setTicker = 1;
                }

                //Set eve name if nameCheck is true
                if ($this->nameEnforce === 'true') {
                    $nameEnforce = 1;
                }
                $role = null;

                $roles = @$guild->roles;
                $member = @$guild->members->get('id', $userID);
                if (null === $member) {
                    $this->message->reply("**Failure:** You're not a member of the correct guild.");
                    return null;
                }
                $eveName = characterName($charID);
                if (null === $eveName) {
                    $this->message->reply('**Failure:** Unable to auth at this time, ESI is down. Please try again later.');
                    return null;
                }
                foreach ($this->authGroups as $authGroup) {
                    //Check if it's set to match corp and alliance
                    if ($authGroup['corpID'] !== 0 && $authGroup['allianceID'] !== 0) {
                        //Check if corpID matches
                        if ($corpID === $authGroup['corpID'] && $allianceID === $authGroup['allianceID']) {
                            foreach ($roles as $role) {
                                if ((string) $role->name === (string) $authGroup['corpMemberRole']) {
                                    $member->addRole($role);
                                }
                                if ((string) $role->name === (string) $authGroup['allyMemberRole']) {
                                    $member->addRole($role);
                                    $role = 'corp/ally';
                                }
                            }
                            break;
                        }
                    } elseif ($authGroup['corpID'] !== 0 || $authGroup['allianceID'] !== 0) {
                        //Check if corpID matches
                        if ($corpID === $authGroup['corpID']) {
                            foreach ($roles as $role) {
                                if ((string) $role->name === (string) $authGroup['corpMemberRole']) {
                                    $member->addRole($role);
                                    $role = 'corp';
                                }
                            }
                            break;
                        }
                        //Check if allianceID matches
                        if ($allianceID === $authGroup['allianceID'] && $authGroup['allianceID'] !== 0) {
                            foreach ($roles as $role) {
                                if ((string) $role->name === (string) $authGroup['allyMemberRole']) {
                                    $member->addRole($role);
                                    $role = 'ally';
                                }
                            }
                            break;
                        }
                    }
                }
                //check for standings based roles
                if ($this->standingsBased === 'true' && $role === null) {
                    $allianceContacts = getContacts($allianceID);
                    $corpContacts = getContacts($corpID);
                    foreach ($roles as $role) {
                        if ((@(int) $allianceContacts['standing'] === 5 || @(int) $corpContacts['standing'] === 5) && (string) $role->name === (string) $this->config['plugins']['auth']['standings']['plus5Role']) {
                            $member->addRole($role);
                            $role = 'blue';
                            break;
                        }
                        if ((@(int) $allianceContacts['standing'] === 10 || @(int) $corpContacts['standing'] === 10) && (string) $role->name === (string) $this->config['plugins']['auth']['standings']['plus10Role']) {
                            $member->addRole($role);
                            $role = 'blue';
                            break;
                        }
                        if ((@(int) $allianceContacts['standing'] === -5 || @(int) $corpContacts['standing'] === -5) && (string) $role->name === (string) $this->config['plugins']['auth']['standings']['minus5Role']) {
                            $member->addRole($role);
                            $role = 'red';
                            break;
                        }
                        if ((@(int) $allianceContacts['standing'] === -10 || @(int) $corpContacts['standing'] === -10) && (string) $role->name === (string) $this->config['plugins']['auth']['standings']['minus10Role']) {
                            $member->addRole($role);
                            $role = 'red';
                            break;
                        }
                    }
                    if ($role === null) {
                        foreach ($roles as $role) {
                            if ((string) $role->name === (string) $this->config['plugins']['auth']['standings']['neutralRole']) {
                                $member->addRole($role);
                                $role = 'neut';
                                break;
                            }
                        }
                    }
                }
                if (null !== $role) {
                    $guild->members->save($member);
                    insertUser($this->db, $this->dbUser, $this->dbPass, $this->dbName, $userID, $charID, $eveName, $role);
                    disableReg($this->db, $this->dbUser, $this->dbPass, $this->dbName, $code);
                    $msg = ":white_check_mark: **Success:** {$eveName} has been successfully authed.";
                    $this->logger->addInfo("auth: {$eveName} authed");
                    $this->message->reply($msg);
                    //Add ticker if set and change name if nameEnforce is on
                    if (isset($setTicker) || isset($nameEnforce)) {
                        if (isset($setTicker) && isset($nameEnforce)) {
                            $nick = "[{$corpTicker}] {$eveName}";
                        } elseif (null === $setTicker && isset($nameEnforce)) {
                            $nick = "{$eveName}";
                        } elseif (isset($setTicker) && !isset($nameEnforce)) {
                            $nick = "[{$corpTicker}] {$userName}";
                        }
                    }
                    if (null !== $nick) {
                        queueRename($userID, $nick, $this->guild);
                    }
                    return null;
                }
                $this->message->reply('**Failure:** There are no roles available for your corp/alliance.');
                $this->logger->addInfo('Auth: User was denied due to not being in the correct corp or alliance ' . $eveName);
                return null;
            }
            $this->message->reply('**Failure:** There was an issue with your code.');
            $this->logger->addInfo('Auth: User was denied due to the code being invalid ' . $userName);
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
            'name' => 'auth',
            'trigger' => $this->triggers,
            'information' => 'SSO based auth system. ' . $this->ssoUrl . ' Visit the link and login with your main EVE account, select the correct character, and put the !auth <string> you receive in chat.'
        );
    }
}
