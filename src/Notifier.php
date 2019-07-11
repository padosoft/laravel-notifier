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
        $theme = (isset($notification['theme']) ? $notification['theme'] : 'metroui');
        $timeout = ((isset($notification['timeout']) && $notification['timeout'] != '' && is_int($notification['timeout']) && $notification['timeout']>0) ? $notification['timeout'] : false);
		$text = (isset($notification['text']) ? str_replace("'", "\\'", $notification['text']) : null);
        $message = (isset($notification['message']) ? $notification['message'] : '');
        $type = (isset($notification['type']) ? $notification['type'] : 'info');
		$layout = (isset($notification['layout']) ? $notification['layout'] : 'topRight');
		
        $output = "				
                $(function () {
                    new Noty({            
						theme: '".$theme."',
						timeout: '".$timeout."',
						type: '".$type."',
						layout: '".$layout."',
						text: '".$text."'
					}).show();            
                });				
				";
        
        return $output;
    }

    /**
     * Add a notification
     *
     * @param string $type Could be error, info, success, or warning.
     * @param string $text The notification's message
     * @param string $title The notification's title
     * @param array $options
     * @param bool $onlyNextRequest if true(default), se the notification in session only for the next request
     *
     */
    public function add($theme, $timeout, $type, $layout, $text, array $options = [], bool $onlyNextRequest=true)
    {
        if ($type=='') {
            $type = 'info';
        }

        $this->notifications[] = [
            'theme' => $theme,
            'timeout' => $timeout,
            'type' => $type,
            'layout' => $layout,
            'text' => $text,
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
    public function info($text, array $options = [])
    {
        $theme = ( isset($options['theme']) && $options['theme'] != '' ) ? $options['theme'] : 'metroui';
        $timeout = ( isset($options['timeout']) && $options['timeout'] != '' && is_int($options['timeout']) ) ? $options['timeout'] : 0;
        $position = ( isset($options['position']) && $options['position'] != '' ) ? $options['position'] : 'topRight';

        $this->add($theme, $timeout, 'info', $position, $text, array(), true);
    }

    /**
     * Shortcut for adding an error notification
     *
     * @param string $message The notification's message
     * @param string $title The notification's title
     * @param array $options
     */
    public function error($text, array $options = [])
    {
        $theme = ( isset($options['theme']) && $options['theme'] != '' ) ? $options['theme'] : 'metroui';
        $timeout = ( isset($options['timeout']) && $options['timeout'] != '' && is_int($options['timeout']) ) ? $options['timeout'] : 0;
        $position = ( isset($options['position']) && $options['position'] != '' ) ? $options['position'] : 'topRight';

        $this->add($theme, $timeout, 'error', $position, $text, array(), true);
    }

    /**
     * Shortcut for adding a warning notification
     *
     * @param string $message The notification's message
     * @param string $title The notification's title
     * @param array $options
     */
    public function warning($text, array $options = [])
    {
        $theme = ( isset($options['theme']) && $options['theme'] != '' ) ? $options['theme'] : 'metroui';
        $timeout = ( isset($options['timeout']) && $options['timeout'] != '' && is_int($options['timeout']) ) ? $options['timeout'] : 0;
        $position = ( isset($options['position']) && $options['position'] != '' ) ? $options['position'] : 'topRight';

        $this->add($theme, $timeout, 'warning', $position, $text, array(), true);
    }

    /**
     * Shortcut for adding a success notification
     *
     * @param string $message The notification's message
     * @param string $title The notification's title
     * @param array $options
     */
    public function success($text, array $options = [])
    {
        $theme = ( isset($options['theme']) && $options['theme'] != '' ) ? $options['theme'] : 'metroui';
        $timeout = ( isset($options['timeout']) && $options['timeout'] != '' && is_int($options['timeout']) ) ? $options['timeout'] : 0;
        $position = ( isset($options['position']) && $options['position'] != '' ) ? $options['position'] : 'topRight';

        $this->add($theme, $timeout, 'success', $position, $text, array(), true);
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
