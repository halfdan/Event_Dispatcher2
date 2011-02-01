<?PHP
/**
 * Example of Event_Dispatcher2 using a lambda function
 * callback.
 *
 * @package    Event_Dispatcher2
 * @subpackage Examples
 * @author     Fabian Becker <halfdan@xnorfz.de>
 */

/**
 * load Event_Dispatcher package
 */
require_once __DIR__ . '/../Event/Dispatcher.php';

/**
 * example sender
 */
class Sender
{
    private $_dispatcher = null;

    public function __construct($dispatcher)
    {
        $this->_dispatcher = $dispatcher;
    }

    public function foo()
    {
        $notification = &$this->_dispatcher->post($this, 'onFoo', 'Some Info...');
        echo "notification::foo is {$notification->foo}\n";
    }
}

/**
 * example observer
 */
class Receiver
{
    public $foo;
    
    function notify(&$notification)
    {
        echo "Received notification\n";
        echo "Receiver::foo is {$this->foo}\n";
        $notification->foo = 'bar';
    }
}

$dispatcher = Event_Dispatcher::getInstance();

$sender = new Sender($dispatcher);
$receiver = new Receiver();
$receiver->foo = 42;

$lambda = function(&$notification) use ($receiver)
{ 
	echo "Function callback called!\n"; 
	$receiver->notify(&$notification); 
};

// make sure you are using an ampersand here!
$dispatcher->addObserver($lambda);

$receiver->foo = 'bar';

echo "Sender->foo()\n";
$sender->foo();
?>
