<?php

namespace Padosoft\Laravel\Notification\Notifier;

use Illuminate\Session\SessionManager;

class Notifier
{

    /**
     * Added notifications
     *
     * @var array
     */
    protected $notifications = [];

    /**
     * Added notifications
     *
     * @var array
     */
    protected $options = [];

    /**
     * Illuminate Session
     *
     * @var \Illuminate\Session\SessionManager
     */
    protected $session;

    /**
     * Constructor
     *
     * @param \Illuminate\Session\SessionManager $session
     *
     * @internal param \Illuminate\Session\SessionManager $session
     */
    public function __construct(SessionManager $session)
    {
        $this->session = $session;
    }

    /**
     * Render all the notifications' script to insert into script tag
     *
     * @return string
     *
     */
    public function render() : string
    {
        $notifications = $this->session->get('laravel::notifications');
        if (!$notifications) {
            $notifications = [];
        }

        $output = '';

        foreach ($notifications as $notification) {

            $output .= $this->renderNotification($notification);
        }

        return $output;
    }

    /**
     * Render the script of given notification to insert into script tag
     *
     * @param $notification
     * @return string
     */
    public function renderNotification($notification): string
    {
        $title =  (isset($notification['title']) ? str_replace("'", "\\'",
            htmlentities($notification['title'])) : null) ;
        $message = $notification['message'];
        $type = $notification['type'];

        $output = "
                $(function () {
                    new Noty({            
                        theme: 'metroui',
                        timeout: 2000,
                        type: 'info',
                        layout: 'topRight',
                        text: 'Azione effettuata'
                    }).show();                                                                            
                });";
        /*
        new Notifier({
            title: '" .$title. "',
            text: '" . $message . "',
            type: '" . $type . "'
        });
        */        
        return $output;
    }

    /**
     * Add a notification
     *
     * @param string $type Could be error, info, success, or warning.
     * @param string $message The notification's message
     * @param string $title The notification's title
     * @param array $options
     * @param bool $onlyNextRequest if true(default), se the notification in session only for the next request
     *
     */
    public function add($type, $message, $title = null, array $options = [], bool $onlyNextRequest=true)
    {
        if ($type=='') {
            $type = 'info';
        }

        $this->notifications[] = [
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'options' => $options
        ];

        if($onlyNextRequest){
            $this->session->flash('laravel::notifications', $this->notifications);
        }else{
            $this->session->put('laravel::notifications', $this->notifications);
        }
    }

    /**
     * Shortcut for adding an info notification
     *
     * @param string $message The notification's message
     * @param string $title The notification's title
     * @param array $options
     */
    public function info($message, $title = null, array $options = [])
    {
        $this->add('info', $message, $title, $options);
    }

    /**
     * Shortcut for adding an error notification
     *
     * @param string $message The notification's message
     * @param string $title The notification's title
     * @param array $options
     */
    public function error($message, $title = null, array $options = [])
    {
        $this->add('error', $message, $title, $options);
    }

    /**
     * Shortcut for adding a warning notification
     *
     * @param string $message The notification's message
     * @param string $title The notification's title
     * @param array $options
     */
    public function warning($message, $title = null, array $options = [])
    {
        $this->add('warning', $message, $title, $options);
    }

    /**
     * Shortcut for adding a success notification
     *
     * @param string $message The notification's message
     * @param string $title The notification's title
     * @param array $options
     */
    public function success($message, $title = null, array $options = [])
    {
        $this->add('success', $message, $title, $options);
    }

    /**
     * Clear all notifications
     * @param bool $withSession if true (default) clean notifications in session too.
     */
    public function clear(bool $withSession = true)
    {
        $this->notifications = [];

        if($withSession){
            $this->session->forget('laravel::notifications');
        }
    }
}
