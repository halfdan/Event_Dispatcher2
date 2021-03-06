<?PHP
/**
 * example that show how to use objects as observers without
 * loosing references
 *
 * @package    Event_Dispatcher
 * @subpackage Examples
 * @author     Stephan Schmidt <schst@php.net>
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
        $notification = $this->_dispatcher->post($this, 'onFoo', 'Some Info...');
        echo "notification::foo is {$notification->foo}\n";
    }
}

/**
 * example observer
 */
class Receiver
{
    public $foo;
    
    function notify($notification)
    {
        echo "received notification\n";
        echo "receiver::foo is {$this->foo}\n";
        $notification->foo = 'bar';
    }
}

$dispatcher = Event_Dispatcher::getInstance();

$sender = new Sender($dispatcher);
$receiver = new Receiver();
$receiver->foo = 42;

// make sure you are using an ampersand here!
$dispatcher->addObserver(array($receiver, 'notify'));

$receiver->foo = 'bar';

echo "sender->foo()\n";
$sender->foo();
?>
