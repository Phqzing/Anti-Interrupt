# Anti-Interrupt
Prevent players from interrupting other peoeple's fights.

# Config / Settings
```
---
#set this to false if you don't want to send a message if someone tries to interrupt a fight
send-message: true

#set to:
#true - send the message in chat
#false - send the message as a Popup
send-in-chat: true

#this is the message that will be sent to the player who tries to interrupt
#{damager} - the player that is trying to interrupt
#{victim} - the player he is trying to interrupt
message: "The player {victim} is fighting someone else"

#if you have a CombatLogger plugin I highly suggest to match the timer with the timer for the CombatLogger plugin
timer: 15

#if you want to disable the anti-interrupt in a world just put the world name here
#If you don't want to disable the plugin in any world just leave it blank or just don't edit it
#Note: the world name must be identical to the one in game
disabled-words:
 - "example1"
 - "example2"
...
```

# Features
- send message when a player tries to interrupt (can be disabled)
- can be disabled in any world
- can change timer
