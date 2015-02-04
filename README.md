# Secure-Online-Banking
#### Watch demo video on YouTube!
[![Watch a demo video here!](http://img.youtube.com/vi/c-vzp82HNEM/0.jpg)](https://www.youtube.com/watch?v=c-vzp82HNEM)

##Online banking portal implemented in PHP and MySQL
The main feutures of the portal are as follows. 
#####From the client perspective:
 Registration, Transfer funds between account, View history of transactions, View balance, Recieving Secure transaction codes by email and password protected PDF
 Downloading SCS application (See Smart Card Simulator below), Generating activation code for SCS, Generating token for SCS.
#####From the administrator and employees perspective
Activate new registred users, View clients transaction history, Confirm transactions over 10K, Initialise balance (just for testing).
###Security Measures
#####Attack #1: Session hijacking, session fixation, cookies attributes, exposed session vars, CSRF, logout functionality
#####Countermeasure:​
Prevent session fixation, renew the session after user logged in, destroy session after logout, set session cookies as httponly, logout after detecting inactivity(no request in few minutes). Beside that, extensive static reviews revealed no exposed session vars. As the session variable is readonly the CSRF attack could not hijack the session too.
#####Attack #2: Exposure of sensitive data while handling error
#####Countermeasure:​
We have reviewed our error messages manually and ensured no disclosure of critical data is occurring in our application. In PHP side we are silently collecting exceptions occurred while decryption so non­interference property has been satisfied. Samwise, in all PHP pages we are avoiding sensitive information disclosure.
#####Attack #3: Bypass role based access control
#####Countermeasure:
In our online banking platform three roles are defined as following client, employee and admin. The actions that each role can perform is clearly explained in our use case documents. Bypassing role management system can cause disasters in critical applications such as online banking. We are using P​HP­RBAC access control library for our role based access control system. and evaluated user permissions on each page with respect to the
actions can be triggered on that page.
#####Attack #4: Insecure Communication Channel
#####Countermeasure:​
We have configured the server to run with SSL. Therefore from now on, all the communication between client and server is encrypted. Any network sniffing will not yield any sensitive data any more.
#####Attack #5 Testing Directory traversal/file include
#####Countermeasure:​
We have added an access configuration file on the server in order to prevent unauthorized users
#####Attack #6 SQL injection
#####Countermeasure:​
We have used prepared statements both on UI and C parser. We also filter the input with the following functions:
$user_id = stripslashes ( $user_id );
$user_id = mysql_real_escape_string ( $user_id );
#####Attack #7 Clickjacking
#####Countermeasure:​
As described in OWASP recommendations, we have included header('X­Frame­Options: DENY'); directive in every header served by the server.
#####Attack #8 XSS
#####Countermeasure:​
Against XSS attacks we are using HTMLPurifier. We have rechecked the code for the warnings noted by team 15 but as expected, the malicious code is transformed into simple text ­ no attack is possible.
#####Attack #9 Passwords discovery
#####Countermeasure: 
Strong password policy is imposed to the user when registering for the first time. There is a brute force checking function for login. This is simply done by storing login attempts and their time in the database. When a user tries to login for more than 5 times within a one hour timespan, then user with the username the potential “attacker” used is deactivated and only an administrator can re­activate him/her.
Secure password reset process (Added Google reCaptcha and implemented more secure token for the user authorization) For more details read the fixes chapter.
##Smart Card Simulator written in Java
We have implemented a very secure transaction code generator which uses AES 128bit encryption (crypto is used right!). 
To achieve this security of high level, we first explicitly ask the user to generate an a​ctivation key​on the profile setup phase. 
This key authenticates the user with the SCS. All the further encryption is done using Activation key. 
Nonetheless, at any time user can revoke the key to ensure the key is not compromised.
The activation key is being stored on the client computer in a secure repository­ all encrypted using client p​ersonal pin​plus a d​evice unique identifier.​
In addition to the activation key, a s​ecure token​is required for each tan generation to be provided to the SCS. 
This token contains the server current date and time in UTC format. 
This token is not only used for checking the lifespan of the tan on the server, but also to authenticate the server on the client side to prevent MITM attack.
Our SCS implementation follows main principles of object­oriented programing, very high cohesion and very low coupling, along with full unit tests. Indeed, all dependencies are to Interfaces rather than actual classes. We have divided the solution into meaningful self­describing packages as crypto, tan, ui, host, test.
###Security Measures
#####Attack #1: Reply Attack
#####Countermeasure:​
A UTC time has been placed in the server token(which is required to generate tans) so that the lifespan of the messages can be verified on the server side upon transaction request. In addition to that, all used tans are being stored in the database so a used tan can not be reused.
#####Attack #2: Man in the middle attack
#####Countermeasure:​
Mutual authentication on both client and server. A secure random 128bit key is generated on the server which must be entered to the SCS as activation key. SCS generated tans are encrypted using this key such that server is able to authenticate the client (SCS). On the SCS side, it is required to have a token to be able to generate tans. The end­user can readily generate token which is valid for 10 minutes in their profile page. Apparently, any attempt to impersonate either of client or server entities without compromised shared key will fail.
Therefore, we are highly confident that our system is secure to MITM attack while the secret key between client and server is not compromised.
#####Attack #3: Impersonation
#####Countermeasure:​
Each client is required to get the activation key from the server which is a secure random 128 bits key.
SCS uses AES 128bit encryption for generating client tans. Each client obtains a secure random activation key which is being used in their client side tan generator app. To deal with the cases when this key has been compromised, the activation key can be revoked by the client on demand.
#####Attack #4: Padding Oracle Attack
#####Countermeasure:​
To avoid ciphertext tampering we have added hmac of the encrypted tan to the ciphertext. So, before attempting to decrypt, the ciphertext server ensures the integrity of the tan.
#####Attack #5: Identical ciphertext due to constant iv
#####Countermeasure:​
Secure tans generated using our SCS are using random initialization vectors(iv). The iv is included in the ciphertext so that server can retrieve it.
10
#####Attack #6: Timing attack while processing SCS generated tans on the server
#####Countermeasure:​
when server is evaluating tans, to avoid timing attacks a timely secure compareStrings method has been employed to block any side channel to gain information about our cryptosystem.

##Batch Transaction Application written in C++ 
We have implemented a command line application for processing batch transaction files. This application is being called by the php server as soon as clients uploading batch transaction files

##OWASP Testing Status 
Following table demonstrates the tests that we have performed on our secure online banking.


| Ref. No. 	| Category 	| Test Name 	| Status 	|
|----------	|------------------	|---------------------------------------------------------------------------	|------------	|
|  	|  	|  	|  	|
| 4.3 	|  	| Configuration and Deploy Management Testing 	|  	|
| 4.3.2 	| OTG-CONFIG-002 	| Test Application Platform Configuration 	| Secure 	|
| 4.3.3 	| OTG-CONFIG-003 	| Test File Extensions Handling for Sensitive Information 	| Secure 	|
| 4.3.4 	| OTG-CONFIG-004 	| Backup and Unreferenced Files for Sensitive Information 	| Secure 	|
| 4.3.5 	| OTG-CONFIG-005 	| Enumerate Infrastructure and Application Admin Interfaces 	| Secure 	|
| 4.3.6 	| OTG-CONFIG-006 	| Test HTTP Methods 	| Secure 	|
| 4.3.7 	| OTG-CONFIG-007 	| Test HTTP Strict Transport Security 	| Secure 	|
| 4.3.8 	| OTG-CONFIG-008 	| Test RIA cross domain policy 	| NA 	|
|  	|  	|  	|  	|
| 4.4 	|  	| Identity Management Testing 	|  	|
| 4.4.1 	| OTG-IDENT-001 	| Test Role Definitions 	| Secure 	|
| 4.4.2 	| OTG-IDENT-002 	| Test User Registration Process 	| Fixed 	|
| 4.4.3 	| OTG-IDENT-003 	| Test Account Provisioning Process 	| Secure 	|
| 4.4.4 	| OTG-IDENT-004 	| Testing for Account Enumeration and Guessable User Account 	| Secure 	|
| 4.4.5 	| OTG-IDENT-005 	| Testing for Weak or unenforced username policy 	| Secure 	|
|  	|  	|  	|  	|
| 4.5 	|  	| Authentication Testing 	|  	|
| 4.5.1 	| OTG-AUTHN-001 	| Testing for Credentials Transported over an Encrypted Channel 	| Secure 	|
| 4.5.2 	| OTG-AUTHN-002 	| Testing for default credentials 	| Secure 	|
| 4.5.3 	| OTG-AUTHN-003 	| Testing for Weak lock out mechanism 	| Secure 	|
| 4.5.4 	| OTG-AUTHN-004 	| Testing for bypassing authentication schema 	| Fixed 	|
| 4.5.5 	| OTG-AUTHN-005 	| Test remember password functionality 	| Secure 	|
| 4.5.6 	| OTG-AUTHN-006 	| Testing for Browser cache weakness 	| Secure 	|
| 4.5.7 	| OTG-AUTHN-007 	| Testing for Weak password policy 	| Secure 	|
| 4.5.8 	| OTG-AUTHN-008 	| Testing for Weak security question/answer 	| NA 	|
| 4.5.9 	| OTG-AUTHN-009 	| Testing for weak password change or reset functionalities 	| Fixed 	|
| 4.5.10 	| OTG-AUTHN-010 	| Testing for Weaker authentication in alternative channel 	| NA 	|
|  	|  	|  	|  	|
| 4.6 	|  	| Authorization Testing 	|  	|
| 4.6.1 	| OTG-AUTHZ-001 	| Testing Directory traversal/file include 	| Fixed 	|
| 4.6.2 	| OTG-AUTHZ-002 	| Testing for bypassing authorization schema 	| Secure 	|
| 4.6.3 	| OTG-AUTHZ-003 	| Testing for Privilege Escalation 	| Secure 	|
| 4.6.4 	| OTG-AUTHZ-004 	| Testing for Insecure Direct Object References 	| NA 	|
|  	|  	|  	|  	|
| 4.7 	|  	| Session Management Testing 	|  	|
| 4.7.1 	| OTG-SESS-001 	| Testing for Bypassing Session Management Schema 	| Secure 	|
| 4.7.2 	| OTG-SESS-002 	| Testing for Cookies attributes 	| Secure 	|
| 4.7.3 	| OTG-SESS-003 	| Testing for Session Fixation 	| Secure 	|
| 4.7.4 	| OTG-SESS-004 	| Testing for Exposed Session Variables 	| Secure 	|
| 4.7.5 	| OTG-SESS-005 	| Testing for Cross Site Request Forgery 	| Fixed 	|
| 4.7.6 	| OTG-SESS-006 	| Testing for logout functionality 	| Secure 	|
| 4.7.7 	| OTG-SESS-007 	| Test Session Timeout 	| Secure 	|
| 4.7.8 	| OTG-SESS-008 	| Testing for Session puzzling 	| Secure 	|
|  	|  	|  	|  	|
| 4.8 	|  	| Data Validation Testing 	|  	|
| 4.8.1 	| OTG-INPVAL-001 	| Testing for Reflected Cross Site Scripting 	| Secure 	|
| 4.8.2 	| OTG-INPVAL-002 	| Testing for Stored Cross Site Scripting 	| Secure 	|
| 4.8.3 	| OTG-INPVAL-003 	| Testing for HTTP Verb Tampering 	| Secure 	|
| 4.8.4 	| OTG-INPVAL-004 	| Testing for HTTP Parameter pollution 	| Secure 	|
| 4.8.5 	| OTG-INPVAL-005 	| Testing for SQL Injection 	| Fixed 	|
| 4.8.5.2 	|  	| MySQL Testing 	|  	|
| 4.8.6 	| OTG-INPVAL-006 	| Testing for LDAP Injection 	| NA 	|
| 4.8.7 	| OTG-INPVAL-007 	| Testing for ORM Injection 	| NA 	|
| 4.8.8 	| OTG-INPVAL-008 	| Testing for XML Injection 	| NA 	|
| 4.8.9 	| OTG-INPVAL-009 	| Testing for SSI Injection 	| NA 	|
| 4.8.10 	| OTG-INPVAL-010 	| Testing for XPath Injection 	| NA 	|
| 4.8.11 	| OTG-INPVAL-011 	| IMAP/SMTP Injection 	| NA 	|
|  	|  	|  	|  	|
| 4.8.12 	| OTG-INPVAL-012 	| Testing for Code Injection 	| Secure 	|
| 4.8.12.1 	|  	| Testing for Local File Inclusion 	| Secure 	|
| 4.8.12.2 	|  	| Testing for Remote File Inclusion 	|  	|
| 4.8.13 	| OTG-INPVAL-013 	| Testing for Command Injection 	|  	|
| 4.8.14 	| OTG-INPVAL-014 	| Testing for Buffer overflow 	| Secure 	|
| 4.8.14.1 	|  	| Testing for Heap overflow 	| Secure 	|
| 4.8.14.2 	|  	| Testing for Stack overflow 	| Secure 	|
| 4.8.14.3 	|  	| Testing for Format string 	| Secure 	|
| 4.8.15 	| OTG-INPVAL-015 	| Testing for incubated vulnerabilities 	| Secure 	|
| 4.8.16 	| OTG-INPVAL-016 	| Testing for HTTP Splitting/Smuggling 	| Not Tested 	|
|  	|  	|  	|  	|
| 4.9 	|  	| Error Handling 	|  	|
| 4.9.1 	| OTG-ERR-001 	| Analysis of Error Codes 	| Fixed 	|
| 4.9.2 	| OTG-ERR-002 	| Analysis of Stack Traces 	| Secure 	|
|  	|  	|  	|  	|
| 4.1 	|  	| Cryptography 	|  	|
| 4.10.1 	| OTG-CRYPST-001 	| Testing for Weak SSL/TSL Ciphers, Insufficient Transport Layer Protection 	| Secure 	|
| 4.10.2 	| OTG-CRYPST-002 	| Testing for Padding Oracle 	| Fixed 	|
| 4.10.3 	| OTG-CRYPST-003 	| Testing for Sensitive information sent via unencrypted channels 	| Secure 	|
|  	|  	| Testing for not using a random iv 	| Fixed 	|
|  	|  	|  	|  	|
| 4.11 	|  	| Business Logic Testing 	|  	|
| 4.11.1 	| OTG-BUSLOGIC-001 	| Test Business Logic Data Validation 	| Secure 	|
| 4.11.2 	| OTG-BUSLOGIC-002 	| Test Ability to Forge Requests 	| Secure 	|
| 4.11.3 	| OTG-BUSLOGIC-003 	| Test Integrity Checks 	| Secure 	|
| 4.11.4 	| OTG-BUSLOGIC-004 	| Test for Process Timing 	| Secure 	|
| 4.11.5 	| OTG-BUSLOGIC-005 	| Test Number of Times a Function Can be Used Limits 	| Secure 	|
| 4.11.6 	| OTG-BUSLOGIC-006 	| Testing for the Circumvention of Work Flows 	| Secure 	|
| 4.11.7 	| OTG-BUSLOGIC-007 	| Test Defenses Against Application Mis-use 	| Secure 	|
| 4.11.8 	| OTG-BUSLOGIC-008 	| Test Upload of Unexpected File Types 	| Secure 	|
| 4.11.9 	| OTG-BUSLOGIC-009 	| Test Upload of Malicious Files 	| Secure 	|
|  	|  	|  	|  	|
| 4.12 	|  	| Client Side Testing 	|  	|
| 4.12.1 	| OTG-CLIENT-001 	| Testing for DOM based Cross Site Scripting 	| Secure 	|
| 4.12.2 	| OTG-CLIENT-002 	| Testing for JavaScript Execution 	| Secure 	|
| 4.12.3 	| OTG-CLIENT-003 	| Testing for HTML Injection 	| Secure 	|
| 4.12.4 	| OTG-CLIENT-004 	| Testing for Client Side URL Redirect 	| Secure 	|
| 4.12.5 	| OTG-CLIENT-005 	| Testing for CSS Injection 	| Secure 	|
| 4.12.6 	| OTG-CLIENT-006 	| Testing for Client Side Resource Manipulation 	| Secure 	|
| 4.12.7 	| OTG-CLIENT-007 	| Test Cross Origin Resource Sharing 	| Secure 	|
| 4.12.8 	| OTG-CLIENT-008 	| Testing for Cross Site Flashing 	| NA 	|
| 4.12.9 	| OTG-CLIENT-009 	| Testing for Clickjacking 	| Fixed 	|
| 4.12.10 	| OTG-CLIENT-010 	| Testing WebSockets 	| NA 	|
| 4.12.11 	| OTG-CLIENT-011 	| Test Web Messaging 	| NA 	|
| 4.12.12 	| OTG-CLIENT-012 	| Test Local Storage 	| Secure 	|

