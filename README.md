## Johnson's Open Lab Hours Management System

An application that will notify the TA via call if the office hour time has started and TA is not here. Offers an admin portal for TA to manage hours and students to quickly join zoom rooms.
Students visit page https://itpwebdev.jianqinggao.com,
Greeting page:


1. It’s open lab hours time and LA is in the zoom meeting 
    1. Students will be redirected to zoom room
2. It’s open lab hours time but LA is NOT in the meeting:
   1. On the student's side, the system shows a countdown of estimated wait time and queue position*. The system also places an automatic call from a US toll-free number to TA’s cell phone to notify TA to join the zoom.
3. The student joined a time period where it has been rescheduled. 
   1. Upon joining, students will be notified that the office hour slot has been rescheduled.
4. Students visited this page outside scheduled time.
   1. The system will tell students that it’s closed now and show all available times.


*queue system to be implemented

## Deployment Guide
Deployment guide \
In config/secret.inc, please fill out credentials:
1. Register for Twilio ID from twilio.com
2. Write TA’s phone number in MY_PHONE_NUMBER
3. username/password for admin login
4. BART notification URL(optional)

```php
define("TWILIO_ACCOUNT_SID", "");
define("TWILIO_AUTH_TOKEN", "");
define("TWILIO_PHONE_NUMBER", "");
define("MY_PHONE_NUMBER", "");
define("CORRECT_USERNAME", "");
define("CORRECT_PASSWORD", "");
define("BART_BASE_URL", "");
```


