# paymentnepal-arwshop
Setup of Paymentnepal plugin.

In service settings inside Paymentnepal merchant area fill in:

* notification URL: http://www.yoursite.com/pmmod.php?pmmod=paymentnepal&act=result&independ=1
* success URL: http://www.yoursite.com/pmmod.php?pmmod=paymentnepal&act=success
* error URL: http://www.yoursite.com/pmmod.php?pmmod=paymentnepal&act=fail

Cpoy paymentnepal catalogue to pm_modules on your site.

In pm_modules/paymentnepal rename pmmod_conf.php.example to pmmod_conf.php

Open it in any text editor, replace secret_key and key to secret_key and payment_key from your service settings.

Inside your site administrator panel in "Settings/Payment Methods" create payment method "Paymentnepal", after that choose paymentnepal from "Plug in payment method" dropdown list, choose USD currency and save settings.
