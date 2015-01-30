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

