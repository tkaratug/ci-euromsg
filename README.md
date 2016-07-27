# EuroMsg SOAP Client for Codeigniter
Codeigniter library to send emails over EuroMessage.

## Installation
Download and place the files in your CI installation, following the same folder structure.

## Configuration
Set authentication details in "application/config/euromsg.php" file.

## Usage

```php
$this->load->library('euromsg');
$this->euromsg->login();
$this->euromsg->from('john@doe.com', 'John Doe');
$this->euromsg->reply('john@doe.com', 'John Doe');
$this->euromsg->to('jane@doe.com', 'Jane Doe');
$this->euromsg->subject('Test Mail');
$this->euromsg->message('This is a test mail.');
$this->euromsg->send();
$this->euromsg->logout();
```