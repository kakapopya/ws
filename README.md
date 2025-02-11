
# END OF SUPPORT

See https://github.com/Lambq/Ws/issues/38

# Ws

Wrapper for [whatsapp/chat-api](https://github.com/mgp25/Chat-API), that allows us send messages through WhatsApp. Thanks guys.

### Installation

**Non Laravel users**

If you're not a Laravel user you only need to run the composer require command in order to install the needed package.

``` 
    composer require Lambq/Ws
```

**Laravel users**

Assuming you already have composer installed on your system, install a new [Laravel](http://laravel.com/) project into `Wsdemo` folder

```
    composer create-project laravel/laravel Wsdemo --prefer-dist
```

Ensure that you set webserver to use `Wsdemo/public` as it's webroot. Now, if you visit http://localhost (or whatever domain name you are using) you should see a nice Laravel welcome message.

Change into your new `Wsdemo` folder.

```
    cd Wsdemo
```

Require the needed package.

``` 
    composer require Lambq/Ws
```

If you get `[InvalidArgumentException] Could not find package Lambq/Ws at any version for your minimum-stability (stable). Check the package spelling or your minimum-stability` you must add these lines to your composer.json an then re-run previous command.

```
    "minimum-stability": "dev",
    "prefer-stable" : true
```

We tell Laravel that there is a Ws ServiceProvider. At the end of `config/app.php` file, in the providers array, add:

```
    'Lambq\Ws\WsServiceProvider'
```

Finally, Into the `config/app.php` file, add to aliases array each of these lines

```
    'Ws' => 'Lambq\Ws\Facades\Laravel\Ws',
    'WsTool' => 'Lambq\Ws\Facades\Laravel\Registration',
```

### Configuration

**Non Laravel users**

If you're using another Framework, different from Laravel, you must put manually the config file `Config/config.php` on the right path.

```
    // Include the composer autoload file
    include_once "vendor/autoload.php";

    // Import the necessary classes
    use Lambq\Ws\Facades\Native\Ws;
    use Lambq\Ws\Facades\Native\Registration;

    // Or, if you want you can add a class alias
    // class_alias('Lambq\Ws\Facades\Native\Ws', 'Ws');
    // class_alias('Lambq\Ws\Facades\Native\Registration', 'Registration');
```

Now, we tell Ws about the config values.

```
    // Of course, you can use the Config class from your favorite Framework.
    $config = __DIR__ . 'config/Ws.php';

    Ws::setConfig($config);
```

By default, the native implementation session use the `$_SESSION` global var, you can override this providing a instance that implements the contract `Xaamin\Ws\Sessions\SessionInterface`.

```
    # Codeigniter 3 example
    
    use CI_Session;
    use Xaamin\Ws\Sessions\SessionInterface;

    class SessionManager implements SessionInterface{

        # The key used in the Session.
        protected $key = 'itnovado_Ws';

        # Session object.
        protected $session;

        public function __construct(CI_Session $session)
        {
            $this->session = $session;
        }

        public function getKey()
        {
            return $this->key;
        }

        public function put($value)
        {
            $this->session->set_userdata($this->getKey(), $value);
        }

        public function pull()
        {
            $data = $this->session->userdata($this->getKey());

            $this->session->unset_userdata($this->getKey());

            return $data;
        }
    }

    // Get some resources
    $ci =& get_instance();
    $ci->load->driver('session');
    
    $sessionManager = new SessionManager($ci->session);

    // Override the default session implementation
    Ws::setSessionManager($sessionManager);
```

**Laravel users**

We need to publish the config file that will allow you to very easily add all your account numbers.

```
    php artisan vendor:publish --provider="Xaamin\Ws\WsServiceProvider" --tag="config"
```

Now everything has been installed, you just need to add your Whatsapp account details into the config file. There will now be a personal config file created for you in `Wsdemo/config/Ws.php`. Open this file and edit the details with your account info. Once saved, you're good to use the API!

### Usage

**Note:** You must create the data storage path specified in configuration file. The path must be writable for webserver.

**Request registration code**

When requesting the code, you can do it via SMS or voice call, in both cases you will receive a code like 123-456, that we will use for register the number.

```
    $number = '5219511552222'; # Number with country code
    $type = 'sms'; # This can be either sms or voice

    $response = WsTool::requestCode($number, $type);

```

Example response:

```
    stdClass Object
    (
        [status] => sent
        [length] => 6
        [method] => sms
        [retry_after] => 1805
    )
```


**Registration**

If you received the code like this 123-456 you should register like this '123456'

```
    $number = '5219511552222'; # Number with country code
    $code = '132456'; # Replace with received code  

    $response = WsTool::registerCode($number, $code);

```

If everything went right, this should be the output:

```
    [status] => ok
    [login] => 34123456789
    [pw] => 0lvOVwZUbvLSxXRk5uYRs3d1E=
    [type] => existing
    [expiration] => 1443256747
    [kind] => free
    [price] => EUR€0.99
    [cost] => 0.89
    [currency] => EUR
    [price_expiration] => 1414897682
```

See the entire registration process on [https://github.com/WHAnonymous/Chat-API/wiki/Ws-Documentation#number-registration](https://github.com/WHAnonymous/Chat-API/wiki/Ws-Documentation#number-registration)


**Send messages**

```
    // Retrieve user data from database, web service, and so on.
    // Dummy method, fake data.
    $user = new stdClass;
    $user->name = 'Benjamín Martínez Mateos';
    $user->phone = '5219512222222';

    $message = "Hello $user->name and welcome to our site";

    $messages = Ws::send($message, function($send) use ($user)
    {
        $send->to($user->phone);

        // Add an audio file
        $send->audio('http://itnovado.com/example.mp3');
 
        // Add an image file
        $send->image('http://itnovado.com/example.jpg', 'Cool image');
        
        // Add a video file
        $send->video('http://itnovado.com/example.mp4', 'Fun video');
 
        // Add a location (Longitude, Latitude)
        $send->location(-89.164138, 19.412405, 'Itnovado Location');
 
        // Add a VCard
        $vcard = new Xaamin\Ws\Media\VCard();
     
        $vcard->set('data', array(
            'first_name' => 'John',
            'last_name' => 'Doe',
            'tel' => '9611111111',
            ));
     
        $send->vcard('Xaamin', $vcard);

        // Add new text message
        $send->message('Thanks for subscribe');
    });
    
    foreach($messages as $message)
    {
        ...
    }

```


**Check for new messages**

```
    $messages = $messages = Ws::getNewMessages();

    if($messages)
    {
        foreach($messages as $message)
        {
            ...
        }
    }
```

**Sync contacts**

```
    $result = Ws::syncContacts(['5219512222222', '5219512222223']);
    
    foreach ($result->existing as $number => $account)
    {
        ... 
    }

    foreach ($result->nonExisting as $number)
    {
        ...
    }
```

Example response

```
    SyncResult Object
    (
        [index] => 0
        [syncId] => 130926960960000000
        [existing] => Array
            (
                [+5219512222222] => 5219512222222@s.whatsapp.net
            )

        [nonExisting] => Array
            (
                [0] => 5219512222223
            )

    )
```

**Missing methods**

You can use all power of the [whatsapp/chat-api](https://github.com/mgp25/Chat-API) getting and instance of `Whatsprot` class. Read the whatsapp/chat-api [wiki](https://github.com/mgp25/Chat-API/wiki) for available methods.

```
    $whatsprot = Ws::gateway();

    // Do stuff...
```


You can use on routes, cli... you got the idea.
