# Anti-Interrupt
Prevent players from interrupting other peoeple's fights.

# Config / Settings
```
---
#set this to false if you don't want to send a message if someome tries to interrupta fight
send-message: true

#set to:
#true - send the message in chat
#false - send the messag as a Popup
send-in-chat: true

#this is the message that will be sent to the player who tries to interrupt
#{damager} - the player that is trying to interrupt
#{victim} - the player he is trying to interrupt
message: "The player {victim} is fighting someone else"

#if you have a CombatTag plugin I highly suggest to match the timer with the timer for the CombatTag plugin
timer: 15
...
```
