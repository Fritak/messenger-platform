Facebook Messenger Platform PHP API for bots.
========================

This code is an implementation of the Messenger Platform in a PHP.

INSTALLATION
------------

```
composer require "fritak/messenger-platform"
```

REQUIREMENTS
------------
The minimum requirement is PHP 5.4 on your Web Server.


## SETUP
```php
$userToSendMessage = $userToSendMessage; // an id that was retrieved through the Messenger entry points or through the Messenger callbacks.

// Or you can set an object UserRecipient - you can try Customer Matching, if you have pages_messaging_phone_number permission
$userToSendMessage = new UserRecipient(NULL, 00420123456789);

// This is just an example, this method of getting request is not safe!
$stream  = file_get_contents("php://input");
$request = empty($stream)? $_REQUEST : $stream;

$bot = new \fritak\MessengerPlatform(
        ['accessToken'      => 'token_for_app',
         'webhookToken'     => 'my_secret_token',
         'facebookApiUrl'   => 'https://graph.facebook.com/v2.6/me/' //2.6 is minimum
        ], $request);
```

## BASIC USAGE
```php
// Check if request is subscribe and then return challenge - [documentation](https://developers.facebook.com/docs/messenger-platform/implementation#setup_webhook)
if($bot->checkSubscribe())
{
    print $bot->request->getChallenge();
    exit;
}

// Subscribe the App to a Page. In order for your webhook to receive events for a specific page, you must subscribe your app to the page.
$bot->subscribe();
```

## GETTING MESSAGES
```php
// Messenger is calling your URL, someone is sending a message...
$messages = $bot->getMessagesReceived();
```

## SENDING MESSAGES
```php
// Send a simple text message.
$bot->sendMessage($userToSendMessage, 'Example!');

// Send an image (file).
$bot->sendImage($userToSendMessage, 'http://placehold.it/150x150');

// Send a structured Message - button template.
$buttons = [new Button('Click', Button::TYPE_WEB, 'example.com'), new Button('Click2', Button::TYPE_POSTBACK, 'example.com')];
$bot->sendButton($userToSendMessage, 'Example text... Not too long, hehe.', $buttons);


// Send a structured Message - receipt template.
$elements    = [new ReceiptElement(['title' => 'Panda', 'price' => 9.99]), new ReceiptElement(['title' => 'Bunny', 'price' => 9.99])];
$summary     = new Summary(['total_cost' => 17.98]);
$address     = new Address(['street_1' => 'Queens 1', 'city' => 'Example city', 'postal_code' => '10000', 'state' => 'DO', 'country' => 'CZ']);
$receipt     = new Receipt('User', Rand(1,9999), 'USD', 'card', $elements, $summary, $address, $adjustments, time(), 'example.com');
$adjustments = [new Adjustment(['name' => 'Discount', 'amount' => 2])];

$bot->sendReceipt($userToSendMessage, $receipt);
```

## SENDING MESSAGES WITH StructuredMessage
```php
$bot->sendComplexMessage(new StructuredMessage($userToSendMessage, 
                ['url' => 'http://placehold.it/150x150'], 
                MessageSend::NOTIFICATION_TYPE_SILENT_PUSH,
                StructuredMessage::ATTACHMENT_TYPE_IMAGE));

$bot->sendComplexMeesage(new StructuredMessage($userToSendMessage, 
                [new Element('Example.', 'Example...', 'http://placehold.it/150x150', 'http://placehold.it/150x150', [new Button('Click', Button::TYPE_WEB, 'example.com')])],
                MessageSend::NOTIFICATION_TYPE_SILENT_PUSH,
                StructuredMessage::ATTACHMENT_TYPE_TEMPLATE,
                StructuredMessage::TEMPLATE_PAYLOAD_TYPE_GENERIC));

$bot->sendComplexMeesage(new StructuredMessage($userToSendMessage, 
                ['text' => 'Example text... Not too long, hehe.', 
                 'buttons' => [new Button('Click', Button::TYPE_WEB, 'example.com'), new Button('Click2', Button::TYPE_POSTBACK, 'example.com')]],
                MessageSend::NOTIFICATION_TYPE_SILENT_PUSH,
                StructuredMessage::ATTACHMENT_TYPE_TEMPLATE,
                StructuredMessage::TEMPLATE_PAYLOAD_TYPE_BUTTON));
```