Flow Swiftmailer |version| Documentation
========================================

This package allows to easily use the `Swift Mailer library <http://swiftmailer.org>`_ with Flow applications.

This version of the documentation covering release |release| has been rendered at: |today|

Installation
------------

The package can be installed via composer::

  composer require typo3/swiftmailer

Configuration
-------------

To set up the mail transport to be used, adjust the settings as needed. Without any further
configuration, mails will be sent using ``Swift_MailTransport`` which uses the PHP ``mail()``
function. To use SMTP for sending, follow the following example:

.. code-block:: yaml

  TYPO3:
    SwiftMailer:
      transport:
        type: 'Swift_SmtpTransport'
        options:
          host: 'smtp.example.com'
          port: '465'
          username: 'myaccount@example.com'
          password: 'shoobidoo'
          localDomain: 'example.com'

Further transports are available with Swift Mailer and can be used as well. Their options can
be looked up the Swift Mailer documentation and they can be set by extrapolating from their
setter method names (as in: ``setUsername()`` becomes ``username`` in the options.)

Sending mail
------------

If a transport is configured, sending mail is as simple as this:

* create new ``Neos\SwiftMailer\Message`` instance
* set your sender address with ``setFrom()``
* set a subject line with ``setSubject()``
* set recipients with ``setTo()``, ``setCc()``, ``setBcc()``
* set a body with ``setBody()``
* add attachments with ``attach()``
* send with ``send()``

Here is an example:

.. code-block:: php

  	$mail = new \Neos\SwiftMailer\Message();

  	$mail
  		->setFrom(array($senderAddress => $senderName))
  		->setTo(array($recipientAddress => $recipientName))
  		->setSubject($subject);

  	$mail->setBody($message, 'text/html');
  	$mail->setBody($message, 'text/plain');

  	$mail->send();


Debugging sent mail
-------------------

To debug sent mails, an easy way is to configure the transport to the the mbox handler of
this package. If this is done in a context-specific configuration (for *Development* or a
sub-context on a staging server), it can be safely committed to a VCS:

.. code-block:: yaml

  Neos:
    SwiftMailer:
      transport:
        type: 'Neos\SwiftMailer\Transport\MboxTransport'
        options:
          mboxPathAndFilename: '%FLOW_PATH_DATA%/Persistent/sent-mail'

All sent mails will be added to the configured mbox file and can be read with any client
that can handle the mbox file format.

A second option is to use the ``LoggingTransport``, which logs all mails to the *SystemLog*
of Flow:

.. code-block:: yaml

  Neos:
    SwiftMailer:
      transport:
        type: 'Neos\SwiftMailer\Transport\LoggingTransport'
