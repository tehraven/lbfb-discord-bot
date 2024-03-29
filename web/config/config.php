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

$config = array();

$config['bot'] = array(
    'name' => 'REEEE Bot', // Discord name for your bot (Not yet implemented)
    'game' => 'EVE Online', // Shows the bot "playing" this
    'trigger' => '!', // what trigger is used for commands
    'guild' => 280799069977444352, // guildID
    'token' => 'MzYwMTIyODIzMjQwNTgxMTMx.DKRWXA.b-C_IyckLwO0DpAIceKGLYF945s', //enter the token for your app (https://discordapp.com/developers/applications/me)
    'adminRoles' => array('Leadership'), //enter the roles that you'd like to have access to admin commands
    'restrictedChannels' => [], //bot will not respond in these channels
    'silentMode' => 'false' //set this to true if you want to disable all the chat commands
);

$config['database'] = array(
    'host' => '23.229.199.132',
    'user' => 'tehraven_discord',
    'pass' => '!Br9ephl3',
    'database' => 'tehraven_discord'
);

// Twitter
$config['twitter'] = array(
    'consumerKey' => '',
    'consumerSecret' => '',
    'accessToken' => '',
    'accessTokenSecret' => ''
);

$config['eve'] = array(
    'apiKeys' => array( //Put at least one character API here with access to Mails and Notifications. The more keys added the more often the bot will check for new notifications and mails (The characters must all be in the same corp!)
        'user1' => array(
            'keyID' => '6467048',
            'vCode' => 'G45QAoRBiTgetAHJKVboS4dMGn8K0wiA7NBMfVFpd8YP0fUTAc9UPIRwSlnjZUgX',
            'characterID' => 2112681840
        ),
        'user2' => array(
            'keyID' => '',
            'vCode' => '',
            'characterID' => 0
        ),
        'user3' => array(
            'keyID' => '',
            'vCode' => '',
            'characterID' => 0
        ),
    )
);

$config['enabledPlugins'] = array( // remove the slashes for the plugins you want
    'about', //info on the bot
    'auth', //sso based auth system
    'authCheck', // checks if users have left corp or alliance
    'charInfo', // eve character info using eve-kill
    'sysInfo', // eve system information
    'corpInfo', // eve corp info
    'eveStatus', // tq status message command
    //'periodicStatusCheck', // ....YOU MUST SET A CHANNEL IN THE NOTIFICATIONS SECTION NEAR THE BOTTOM OF THIS FILE.... Bot routinely checks if TQ status changes (reports server downtimes to the notifications channel)
    'help', // bot help program, will list active addons
    'price', // price check tool, works for all items and ships. Can either !pc <itemname> for general, or !<systemname> <item> for more specific
    'time', // global clock with eve time
    //'motd', // shows the MOTD of a listed channel.
    //'evemails', // evemail updater, will post corp and alliance mails to a channel.
    //'fileReader', // Read advanced plugin config section of the wiki
    //'notifications', // eve notifications to a channel, good for warning users of an attack
    //'twitterOutput', // twitter input to stay up to date on eve happenings
    'getKillmails', // show corp killmails in a chat channel
    //'getKillmailsRedis', // beta redisQ based killmail pulling USE AT OWN RISK (DO NOT USE WITH getKillmails also active)
    //'siphons', // report possible siphons, see wiki for more info
    //'siloFull', // report any silos nearing max capacity. Currently only works for silo bonus (amarr) towers
    //'fleetUpOperations', // integrate with fleet up and post any new operations and then ping them when they get close
    //'fleetUpOps', //show upcoming fleet up operations with a message command
    //'rssReader', //Post news to rss feeds
);


$config['plugins'] = array(
    //uses the provided api's to post evemails to a channel
    'evemails' => array(
        'fromIDs' => array(0, 0), // fill in with corp/alliance id's you want info from (have to be accessible with the api)
        'channelID' => 0 // what channel id like these to post too
    ),
    'fileReader' => array(
        'db' => '/tmp/discord.db',
        'channelConfig' => array(
            'pings' => array(
                'default' => true,
                'searchString' => 'broadcast', // The plugin will search for this string and post any messages that contain it. To have the bot share everything change it to false without any quotes.
                'textStringPrepend' => '@everyone |', // this prepend will ping all discord users with access to the channel
                'textStringAppend' => '', // anything ud like to add to the tail end of the bots message
                'channelID' => '' // channel it posts too
            ),
            'supers' => array(
                'default' => false,
                'searchString' => 'supers',
                'textStringPrepend' => '@everyone |',
                'textStringAppend' => '',
                'channelID' => 0
            ),
            'blackops' => array(
                'default' => false,
                'searchString' => 'blops',
                'textStringPrepend' => '@everyone |',
                'textStringAppend' => '',
                'channelID' => 0
            )
        ),
    ),
    //Pull MOTD of channel
    'motd' => array(
        'keyID' => '', //char api keyID (Must have channel on api and mod rights on the char)
        'vCode' => '', //char api vCode
        'characterID' =>'', //ID Of the character with mod rights to the channel, can get this from zkill, look up your char and its /character/#### is your char ID
        'channelname' => '', //name of channel you are requesting, must be IDENTICAL
    ),
    // what channel for eve notifications/also the channel for tq status alerts
    'notifications' => array(
        'channelID' => 0,
        'allianceOnly' => 'false' //change this to true if you'd like to filter out the corp specific notifications (tower spam, etc..)
    ),
    //Spam twitter messages from people you follow to this channel
    'twitterOutput' => array(
        'channelID' => 0 // twitter output channel
    ),
    //Pricecheck tool
    'priceChecker' => array(
        'channelID' => array(0, 0) //If you want to restrict price checker from working in a channel, put that channel's ID here.
    ),
    //SSO Auth
    'auth' => array(
        'url' => 'https://discord.fuelblocks.space/', // put a url here if using sso auth for ur sso page.
        'exempt' => [
            'Alliance Leader', 'Leadership', 'Fleet Commander', 'middle management', 'LBFB corp SEO', 'Quartermaster', 'Logistics'
        ], // role names that are exempt from auth checks (wont be removed by the bot)
        'alertChannel' => 0, // if using periodic check put the channel you'd like the bot to log removing users in. (Recommended you don't use an active chat channel)
        'corpTickers' => 'true', // if "true" bot will automatically add corp tickers to the front of users names at auth.
        'nameEnforce' => 'true', // if "true" bot will automatically rename users to match their ingame name, can be used in conjunction with corpTickers.
        'authGroups' => array(
            'alliance' => array(
                'corpID' => 0, // If you'd like to auth based on CORP put the corp ID here otherwise leave it as 0
                'allianceID' => 99005874, // If you'd like to auth based on ALLIANCE put the alliance ID here otherwise leave it as 0 (Can be used in conjunction with corp)
                'corpMemberRole' => '', // The name of the role your CORP members will be assigned too if the auth plugin is active.
                'allyMemberRole' => 'Memefleet', // The name of the role your ALLIANCE members will be assigned too if the auth plugin is active.
            ),
            'WCM' => array(
                'corpID' => 98141672, // If you'd like to auth based on CORP put the corp ID here otherwise leave it as 0
                'allianceID' => 0, // If you'd like to auth based on ALLIANCE put the alliance ID here otherwise leave it as 0 (Can be used in conjunction with corp)
                'corpMemberRole' => 'WCM', // The name of the role your CORP members will be assigned too if the auth plugin is active.
                'allyMemberRole' => '', // The name of the role your ALLIANCE members will be assigned too if the auth plugin is active.
            ),
            'AG15' => array(
                'corpID' => 98373666, // If you'd like to auth based on CORP put the corp ID here otherwise leave it as 0
                'allianceID' => 0, // If you'd like to auth based on ALLIANCE put the alliance ID here otherwise leave it as 0 (Can be used in conjunction with corp)
                'corpMemberRole' => 'AG15', // The name of the role your CORP members will be assigned too if the auth plugin is active.
                'allyMemberRole' => '', // The name of the role your ALLIANCE members will be assigned too if the auth plugin is active.
            ),
            'BWAM' => array(
                'corpID' => 98053172, // If you'd like to auth based on CORP put the corp ID here otherwise leave it as 0
                'allianceID' => 0, // If you'd like to auth based on ALLIANCE put the alliance ID here otherwise leave it as 0 (Can be used in conjunction with corp)
                'corpMemberRole' => '', // The name of the role your CORP members will be assigned too if the auth plugin is active.
                'allyMemberRole' => 'BWAM', // The name of the role your ALLIANCE members will be assigned too if the auth plugin is active.
            ),
            '.DWA.' => array(
                'corpID' => 98525869, // If you'd like to auth based on CORP put the corp ID here otherwise leave it as 0
                'allianceID' => 0, // If you'd like to auth based on ALLIANCE put the alliance ID here otherwise leave it as 0 (Can be used in conjunction with corp)
                'corpMemberRole' => '.DWA.', // The name of the role your CORP members will be assigned too if the auth plugin is active.
                'allyMemberRole' => '', // The name of the role your ALLIANCE members will be assigned too if the auth plugin is active.
            ),
            'EX LE' => array(
                'corpID' => 98435713, // If you'd like to auth based on CORP put the corp ID here otherwise leave it as 0
                'allianceID' => 0, // If you'd like to auth based on ALLIANCE put the alliance ID here otherwise leave it as 0 (Can be used in conjunction with corp)
                'corpMemberRole' => 'EX LE', // The name of the role your CORP members will be assigned too if the auth plugin is active.
                'allyMemberRole' => '', // The name of the role your ALLIANCE members will be assigned too if the auth plugin is active.
            ),
            'IFRAC' => array(
                'corpID' => 98410503, // If you'd like to auth based on CORP put the corp ID here otherwise leave it as 0
                'allianceID' => 0, // If you'd like to auth based on ALLIANCE put the alliance ID here otherwise leave it as 0 (Can be used in conjunction with corp)
                'corpMemberRole' => 'IFRAC', // The name of the role your CORP members will be assigned too if the auth plugin is active.
                'allyMemberRole' => '', // The name of the role your ALLIANCE members will be assigned too if the auth plugin is active.
            ),
            '-MGS-' => array(
                'corpID' => 98119988, // If you'd like to auth based on CORP put the corp ID here otherwise leave it as 0
                'allianceID' => 0, // If you'd like to auth based on ALLIANCE put the alliance ID here otherwise leave it as 0 (Can be used in conjunction with corp)
                'corpMemberRole' => '-MGS-', // The name of the role your CORP members will be assigned too if the auth plugin is active.
                'allyMemberRole' => '', // The name of the role your ALLIANCE members will be assigned too if the auth plugin is active.
            ),
            'TINIM' => array(
                'corpID' => 98511081, // If you'd like to auth based on CORP put the corp ID here otherwise leave it as 0
                'allianceID' => 0, // If you'd like to auth based on ALLIANCE put the alliance ID here otherwise leave it as 0 (Can be used in conjunction with corp)
                'corpMemberRole' => 'TINIM', // The name of the role your CORP members will be assigned too if the auth plugin is active.
                'allyMemberRole' => '', // The name of the role your ALLIANCE members will be assigned too if the auth plugin is active.
            ),
            'OGLOB' => array(
                'corpID' => 1641100543, // If you'd like to auth based on CORP put the corp ID here otherwise leave it as 0
                'allianceID' => 0, // If you'd like to auth based on ALLIANCE put the alliance ID here otherwise leave it as 0 (Can be used in conjunction with corp)
                'corpMemberRole' => 'OGLOB', // The name of the role your CORP members will be assigned too if the auth plugin is active.
                'allyMemberRole' => '', // The name of the role your ALLIANCE members will be assigned too if the auth plugin is active.
            ),
            'RSE-I' => array(
                'corpID' => 98478981, // If you'd like to auth based on CORP put the corp ID here otherwise leave it as 0
                'allianceID' => 0, // If you'd like to auth based on ALLIANCE put the alliance ID here otherwise leave it as 0 (Can be used in conjunction with corp)
                'corpMemberRole' => 'RSE-I', // The name of the role your CORP members will be assigned too if the auth plugin is active.
                'allyMemberRole' => '', // The name of the role your ALLIANCE members will be assigned too if the auth plugin is active.
            ),
            'BACKT' => array(
                'corpID' => 98508809, // If you'd like to auth based on CORP put the corp ID here otherwise leave it as 0
                'allianceID' => 0, // If you'd like to auth based on ALLIANCE put the alliance ID here otherwise leave it as 0 (Can be used in conjunction with corp)
                'corpMemberRole' => 'BACKT', // The name of the role your CORP members will be assigned too if the auth plugin is active.
                'allyMemberRole' => '', // The name of the role your ALLIANCE members will be assigned too if the auth plugin is active.
            ),
            'SWEVE' => array(
                'corpID' => 98523386, // If you'd like to auth based on CORP put the corp ID here otherwise leave it as 0
                'allianceID' => 0, // If you'd like to auth based on ALLIANCE put the alliance ID here otherwise leave it as 0 (Can be used in conjunction with corp)
                'corpMemberRole' => 'SWEVE', // The name of the role your CORP members will be assigned too if the auth plugin is active.
                'allyMemberRole' => '', // The name of the role your ALLIANCE members will be assigned too if the auth plugin is active.
            ),
            '.9GAG' => array(
                'corpID' => 98416106, // If you'd like to auth based on CORP put the corp ID here otherwise leave it as 0
                'allianceID' => 0, // If you'd like to auth based on ALLIANCE put the alliance ID here otherwise leave it as 0 (Can be used in conjunction with corp)
                'corpMemberRole' => '.9GAG', // The name of the role your CORP members will be assigned too if the auth plugin is active.
                'allyMemberRole' => '', // The name of the role your ALLIANCE members will be assigned too if the auth plugin is active.
            ),
            'JUSTM' => array(
                'corpID' => 98505105, // If you'd like to auth based on CORP put the corp ID here otherwise leave it as 0
                'allianceID' => 0, // If you'd like to auth based on ALLIANCE put the alliance ID here otherwise leave it as 0 (Can be used in conjunction with corp)
                'corpMemberRole' => 'JUSTM', // The name of the role your CORP members will be assigned too if the auth plugin is active.
                'allyMemberRole' => '', // The name of the role your ALLIANCE members will be assigned too if the auth plugin is active.
            ),
            'T.N.S' => array(
                'corpID' => 98507209, // If you'd like to auth based on CORP put the corp ID here otherwise leave it as 0
                'allianceID' => 0, // If you'd like to auth based on ALLIANCE put the alliance ID here otherwise leave it as 0 (Can be used in conjunction with corp)
                'corpMemberRole' => 'T.N.S', // The name of the role your CORP members will be assigned too if the auth plugin is active.
                'allyMemberRole' => '', // The name of the role your ALLIANCE members will be assigned too if the auth plugin is active.
            ),
            'TRPWR' => array(
                'corpID' => 98524710, // If you'd like to auth based on CORP put the corp ID here otherwise leave it as 0
                'allianceID' => 0, // If you'd like to auth based on ALLIANCE put the alliance ID here otherwise leave it as 0 (Can be used in conjunction with corp)
                'corpMemberRole' => 'TRPWR', // The name of the role your CORP members will be assigned too if the auth plugin is active.
                'allyMemberRole' => '', // The name of the role your ALLIANCE members will be assigned too if the auth plugin is active.
            ),
            '260M' => array(
                'corpID' => 98522929, // If you'd like to auth based on CORP put the corp ID here otherwise leave it as 0
                'allianceID' => 0, // If you'd like to auth based on ALLIANCE put the alliance ID here otherwise leave it as 0 (Can be used in conjunction with corp)
                'corpMemberRole' => '260M', // The name of the role your CORP members will be assigned too if the auth plugin is active.
                'allyMemberRole' => '', // The name of the role your ALLIANCE members will be assigned too if the auth plugin is active.
            ),
            'LCPL' => array(
                'corpID' => 98483509, // If you'd like to auth based on CORP put the corp ID here otherwise leave it as 0
                'allianceID' => 0, // If you'd like to auth based on ALLIANCE put the alliance ID here otherwise leave it as 0 (Can be used in conjunction with corp)
                'corpMemberRole' => 'LCPL', // The name of the role your CORP members will be assigned too if the auth plugin is active.
                'allyMemberRole' => '', // The name of the role your ALLIANCE members will be assigned too if the auth plugin is active.
            )
        ),
        'standings' => array(
            'enabled' => 'false', //set to true if you want to allow people to auth based off of corp/alliance standings
            'apiKey' => 'KEYID', //enter the KEYID for whatever above api you'd like to base standings off of
            'plus10Role' => '',
            'plus5Role' => '',
            'neutralRole' => '',
            'minus10Role' => '',
            'minus5Role' => '',
        ),
    ),
    //Killmail posting
    'getKillmails' => array(
        'bigKills' => array(
            'shareBigKills' => 'false', //If you'd like the bot to share eve wide big kills switch this to true
            'bigKillChannel' => 0, //Set the channel the eve wide big kills post too
            'bigKillStartID' => 57000000, //Recommend you set this to a recent killID to prevent it spamming from the beginning of time
        ),
        'groupConfig' => array(
            'alliance' => array(
                'name' => 'Alliance', // insert a label (these must be unique)
                'channel' => 328440638531960833, //killmails post to this channel
                'corpID' => 0, //corpid for killmails
                'allianceID' => 99005874, //allianceid for killmails (Leave as 0 if using it for a corp)
                'lossMails' => 'false', //set as true to post both kills and losses, false to post only kills.
                'startMail' => 64910048, //Put the zkill killID of your latest killmail. Otherwise it will pull from the beginning of time.
                'minimumValue' => 100000000, //Put the minimum isk value for killmails here (Do not put any commas just numbers)
                'minimumlossValue' => 500000000, //Put the minimum isk loss value for killmails here (Do not put any commas just numbers)
                'bigKill' => 1000000000, //Set an isk amount you'd like to consider a high value kill, will alert the channel if any kills/losses hit this amount. (Leave as null if you don't want this feature)
                'bigKillChannel' => 328440638531960833, //what channel does the bot post big kills into (must be set, if ud like to use one channel just put the same u put above here)
            )
        ),
    ),
    //Siphon detection works by looking for multiples of 100 inside standard silos. https://github.com/shibdib/Dramiel/wiki/1b.-Siphon-Detection for more info
    'siphons' => array(
        'groupConfig' => array(
            'group1' => array(
                'channelID' => 0, //siphon alerts post to this channel
                'keyID' => '', //keyID
                'vCode' => '', //vCode
                'prefix' => '', //put @everyone if you'd like everyone to be pinged when a siphon is detected
            ),
            'group2' => array(
                'channelID' => 0, //siphon alerts post to this channel (Leave as 0 if not in use)
                'keyID' => '', //keyID
                'vCode' => '', //vCode
                'prefix' => '', //put @everyone if you'd like everyone to be pinged when a siphon is detected
            ),
            'group3' => array(
                'channelID' => 0, //siphon alerts post to this channel (Leave as 0 if not in use)
                'keyID' => '', //keyID
                'vCode' => '', //vCode
                'prefix' => '', //put @everyone if you'd like everyone to be pinged when a siphon is detected
            ),
        ),
    ),
    //If you'd like low fuel warnings to go to a different channel set this here. Otherwise leave it as null
    'fuel' => array(
        'channelID' => null, //fuel alerts post to this channel
        'skip' => 'false', //if you want fuel notifications to be skipped change this to true
    ),
    //Reports silos nearing max capacity.
    'siloFull' => array(
        'channelID' => 0, //silo alerts post to this channel
        'keyID' => '', //corp api keyID (Must have assets)
        'vCode' => '', //corp api vCode
    ),
    //Fleet up linking will share operations to a specific channel and then reping them when it gets within 30 minutes of form up
    'fleetUp' => array(
        'channelID' => 0, //channel id to ping about operations
        'userID' => 0, //fleet up user id
        'groupID' => 0, //fleet up group id
        'apiKey' => 'xxxxx', //fleet up api code, link to application Dramiel Bot
    ),
    //Post well formatted rss feed links to a channel
    'rssReader' => array(
        'channelID' => 0, //channel id to post rss links
        'rssFeeds' => array( //feel free to add more url's if needed
            'url1' => '',
            'url2' => '',
            'url3' => '',
            'url4' => '',
            'url5' => '',
        )
    ),
);
