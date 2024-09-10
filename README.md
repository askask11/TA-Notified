
## TA Notified
### App Overview

This app enhances the management of office hours by notifying the TA when the session has started but the TA is not present. It calls the TA's phone number(U.S. phone only) and sends notifications via the BARK app. It also provides an admin portal for the TA to manage their office hours, and offers students a quick way to join Zoom rooms.

When students visit [this page](https://itpwebdev.jianqinggao.com), the system responds based on the current office hour status:

**Greeting Page** \

<img width="790" alt="image" src="https://github.com/user-attachments/assets/94361de6-2d86-4a6c-ae7d-85d33534ed05" style="width:50%;">

1. **Office hours are in session and the TA is available**
    - Students are automatically redirected to the Zoom meeting.
    - <img width="612" alt="image" src="https://github.com/user-attachments/assets/423400e1-0b9d-4c29-8b48-19760441a5b9" style="width:40%;">



2. **Office hours are in session but the TA is not present**
    - Students see a countdown displaying the estimated wait time and their queue position (queue system to be implemented).
    - The system automatically places a call from a US toll-free number to notify the TA to join the Zoom session.
    - <img width="861" alt="image" src="https://github.com/user-attachments/assets/e3a7ede9-7018-4777-983e-9e5a246d0b58" style="width:50%;">


3. **The office hour slot has been rescheduled**
    - Students are notified that the office hours have been rescheduled when they visit the page.
    - <img width="643" alt="image" src="https://github.com/user-attachments/assets/59c18273-9166-4eef-a154-a0ed760f6d38" style="width:40%;">


4. **Students visit outside scheduled office hours**
    - The system informs students that office hours are closed and displays the next available time slots.
   <img width="536" alt="image" src="https://github.com/user-attachments/assets/9d78e12d-6378-4fd3-abb5-735536d5d7fb" style="width:40%;">


---

### Deployment Guide
You need:
1. twilio.com account
2. A phone number to receive calls.

To deploy the app, complete the following steps:

1. Create a folder called `config` in the root directory and create file `secret.inc` in `config` fill in the necessary credentials:
    - Register for a Twilio account and provide your Twilio ID and token.
    - Add the TA's phone number to `MY_PHONE_NUMBER`.
    - Set the username and password for admin access.
    - Optionally, provide a BART notification URL.

```php
define("TWILIO_ACCOUNT_SID", ""); // Your Twilio Account SID
define("TWILIO_AUTH_TOKEN", ""); // Your Twilio Auth Token
define("TWILIO_PHONE_NUMBER", ""); // Your Twilio phone number
define("MY_PHONE_NUMBER", ""); // TA’s phone number
define("CORRECT_USERNAME", ""); // Admin username
define("CORRECT_PASSWORD", ""); // Admin password
define("BART_BASE_URL", ""); // (Optional) BART notification URL
```

2. create file `timeslot.json`, `meeting_status.txt`, and `queue.json` in the root directory of the project.
3. Run the following command to install the necessary dependencies:
```bash
composer install
```
