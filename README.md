INSTALLATION
===========

Copy noip.phar to /usr/local/sbin

    cp ./noip.phar /usr/local/sbin
    
Create noip client configuration

    vi /etc/noipclient.ini
    
Example config file

    [noip]
    username=yourusername@domain.com
    password=secretpassword
    hostname=hostname-to-upload
    

Add executable permissions (only for owner)

    chmod u+x /usr/local/sbin/noip.phar
    
Run

    /usr/local/sbin/noip.phar noip:update -f /etc/noipclient.ini

Create crontab file

    vi /etc/cron.d/50-noip
    
Run this script every 5 minutes. We discarded output from stdout. 

    */5     *       *       *       *       CHANGE_TO_OWNER_OF_FILE      /usr/local/sbin/noip.phar noip:update -f /etc/noipclient.ini > /dev/null



TODO
=====

* logging

