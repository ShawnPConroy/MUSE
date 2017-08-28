# MultiUser Shared Environment (MUSE) PHP WebApp
MUSE is a text-based world building MU*-like client/server webapp written in PHP.

## MU*-like Systems

MU*-like systems include Multiuser Dungeons (MUDs), Multiuser Shared Hallucianations (MUSHs) and Multiuser Experience (MUX).

MUSE is releated to MUD/MUSH/MUX (MU*) systems. But most of those systems require a binary to run on a server using a persistent connection. MUSE was created in PHP to run on most web services, and requiring almost no regular administration. Other web based MU* applications are clients to connect traditional persistent connection services.

MUSE allows you to toy with or use a MU*-like system in one easy to maintain package. The advantage is that once you set it up you can forget it, and it will continue running. (That's a strength of PHP.) The draw back is traditional clients cannot connect to it. (Also: it's obviously incomplete at this point.) I have not found any MU* system that is so flexible, easy to maintain and compitable with cheap hosting accountings.

MU* systems share a similar user experience with IF (interactive fiction), but those rely more heavily on scripting and are single-user experiences.

## Requirements

You must have PHP and MySQL on your server. Users must have a web browser with HTML, CSS and JavaScript compatibility (so most browsers used by nearly everyone).

## Installation

1. Create a MySQL user and database, connecting with basic insert and udpate rights.
2. From the `sql/` folder, add either the empty or default world. (Empty world hasn't been tested, and possibly won't work. It needs at least some main room to drop you in to, doesn't it?)
3. Upload the `app/` files to your webserver in some folder, or the root.
4. Copy the `config.php.default' to `config.php`.
5. Edit the file and put in the database username, database name, and the user-facing URI (URL) of where the app is.

You should now be able to go to the app, create a user, and sign in.

## Commands

For the commands that have been included in MUSE, I have linked to a larger description of the command from a [PennMUSH command listing](http://dynamix.xidus.net/penncmd.htm#@dig).

### Explore Commands (server)

* `look` Look around your current location. (Location object listing.) 
* `say` (`"`) say something to everyone in your location.
* `go` (`goto`, `go to`, `move`) Move player to new location.
* `take` Take object from location to inventory.
* `drop` Drop object from inventory to location.

### Build commands (server)
* `examine` Dump item info.
* `@create` Create object.
* `@chown` Change object owner.
* `@destroy` Delete object.
* `@describe` (`@desc`) Set object description.
* `@dig <room name> [= <exit name>;<exit alias>*,<return exit name>;<exit alias>*]`<br/>Create a new room and link. [See @dig description](http://dynamix.xidus.net/penncmd.htm#@dig)
* `@name` Change object name.
* `@open` Create an exit.
* `@link` Link an exit to a room.
* `@set` Set extended info on object. Eg. success or osuccess)

### Debugging commands (client)

* debugon Turns on JavaScript debug mode
* debugoff Turns off JavaScript debug mode
* serverdebugon Turns on sever processing debugging
* serverdebugoff Turns off sever processing debugging
* debugall
* debugall Turns on all debugging
* debugnone Turns off all debugging

## History

In university I toyed with someone's PennMUSH system and thought it was very amusing. It's something I kept coming back to year after year. In 2012/2013 I started to create MUSE. I went through several iterations of the name (World Builder, HoloPage, phpMUSH, HoloText) before settling on MUSE.

There are several MU* engines, including [PennMUSH](http://www.pennmush.org/) and even [Evennia](http://www.evennia.com/), but they all seem to require you to install as a service, which is not compatible with most shared hosting plans. Also, these services can sometimes be tempermental if the server freezes, reboots or is upgraded.

Other apps like [MUD Portal](http://www.mudportal.com/) or [PHudBase-WebMud](http://www.phudbase.com/webmud.php) are web clients that connect to the above engines.

MUSE, on the other hand, is the complete package. Admins don't need to maintain the software, and users don't need to use any tools. Just show up and sign in. It works right in the browser.
