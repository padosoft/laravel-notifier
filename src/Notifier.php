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
     * Flashable added notifications
     *
     * @var array
     */
    protected $flashedNotifications = [];

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
        $flashedNotifications = null;

        if ($this->session->has('laravel::flash-notifications')) {
            $flashedNotifications = $this->session->get('laravel::flash-notifications');
        }
        if ($flashedNotifications !== null) {
            $this->flashedNotifications = $flashedNotifications;
        } else {
            $this->flashedNotifications = [];
        }
        $this->setNotifyFromSession();
    }

    /**
     * Set all notification that are present in session
     *
     * @return void
     */
    public function setNotifyFromSession(): void
    {
        $this->setNotifyErrorFromSession();
        $this->setNotifyWarningFromSession();
        $this->setNotifySuccessFromSession();
        $this->setNotifyInfoFromSession();
    }

    /**
     */
    protected function setNotifyErrorFromSession(): void
    {
        $this->setNotifyFromSessionByLabel('errors');
    }

    /**
     */
    protected function setNotifyWarningFromSession(): void
    {
        $this->setNotifyFromSessionByLabel('warnings');
    }

    /**
     */
    protected function setNotifyInfoFromSession(): void
    {
        $this->setNotifyFromSessionByLabel('info');
    }

    /**
     */
    protected function setNotifySuccessFromSession(): void
    {
        $this->setNotifyFromSessionByLabel('success');
    }
    /**
     * Set all notification of a label type that are present in session
     *
     * @param string $label
     *
     * @return void
     */
    protected function setNotifyFromSessionByLabel(string $label): void
    {

        if (!$this->session->has($label)) {
            return;
        }

        if ($label == 'errors') {
            $errors = $this->session->get('errors')->all();
            foreach ($errors as $error) {
                $this->error($error,false);
            }

            return;
        }


        $messages = session()->get($label);
        if ($label=='warnings'){
            $label='warning';
        }
        if (!is_array($messages)) {
            $this->$label($messages,false);
            return;
        }

        foreach ($messages as $msg) {
            $this->$label($msg,false);
        }
    }

    /**
     * Render all the notifications' script to insert into script tag
     *
     * @return string
     *
     */
    public function render(): string
    {
        $output = [];
        foreach ($this->notifications as $notification) {

            $output[] = $notification;
        }
        foreach ($this->flashedNotifications as $flNotification) {
            $output[] = $flNotification;
        }

        return "window.notifications = " . json_encode($output) . ";";
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
    public function add($theme, $timeout, $type, $layout, $text, $sounds = null, $soundsVolume = null)
    {
        if ($type == '') {
            $type = 'info';
        }

        if ($timeout === null) {
            $timeout = false;
        }

        $notification = [
            'theme' => $theme,
            'timeout' => $timeout,
            'type' => $type,
            'layout' => $layout,
            'text' => $text,
        ];
        if ($sounds !== null) {
            $notification['sources'] = [
                'sounds' => [$sounds],
                'soundsVolume' => $soundsVolume !== null ? $soundsVolume : 0.5,
            ];
        }

        $this->notifications[] = $notification;
    }

    public function addForNextRequest($theme, $timeout, $type, $layout, $text, $sounds = null, $soundsVolume = null)
    {
        if ($type == '') {
            $type = 'info';
        }

        if ($timeout === null) {
            $timeout = false;
        }

        $notification = [
            'theme' => $theme,
            'timeout' => $timeout,
            'type' => $type,
            'layout' => $layout,
            'text' => $text,
        ];
        if ($sounds !== null) {
            $notification['sources'] = [
                'sounds' => [$sounds],
                'soundsVolume' => $soundsVolume !== null ? $soundsVolume : 0.5,
            ];
        }

        if ($this->session->has('laravel::flash-notifications')) {
            $flashNotifications = $this->session->get('laravel::flash-notifications');
        } else {
            $flashNotifications = [];
        }
        $this->session->forget('laravel::flash-notifications');
        $flashNotifications[] = $notification;
        $this->session->flash('laravel::flash-notifications', $flashNotifications);
    }

    /**
     * Shortcut for adding an info notification
     *
     * @param string $message The notification's message
     * @param string $title The notification's title
     * @param array $options
     */
    public function info($text, $onlyNextRequest = false, array $options = [])
    {
        $theme = (isset($options['theme']) && $options['theme'] != '') ? $options['theme'] : 'metroui';
        $timeout = (isset($options['timeout']) && $options['timeout'] != '' && is_int($options['timeout'])) ? $options['timeout'] : false;
        $layout = (isset($options['layout']) && $options['layout'] != '') ? $options['layout'] : 'topRight';
        if ($onlyNextRequest){
            $this->addForNextRequest($theme, $timeout, 'info', $layout, $text, null, null);
            return;
        }
        $this->add($theme, $timeout, 'info', $layout, $text, null, null);
    }

    /**
     * Shortcut for adding an error notification
     *
     * @param string $message The notification's message
     * @param string $title The notification's title
     * @param array $options
     */
    public function error($text, $onlyNextRequest = false, array $options = [])
    {
        $theme = (isset($options['theme']) && $options['theme'] != '') ? $options['theme'] : 'metroui';
        $timeout = (isset($options['timeout']) && $options['timeout'] != '' && is_int($options['timeout'])) ? $options['timeout'] : 0;
        $layout = (isset($options['layout']) && $options['layout'] != '') ? $options['layout'] : 'topRight';
        if ($onlyNextRequest){
            $this->addForNextRequest($theme, $timeout, 'error', $layout, $text, null, null);
            return;
        }
        $this->add($theme, $timeout, 'error', $layout, $text, null, null, $onlyNextRequest);
    }

    /**
     * Shortcut for adding a warning notification
     *
     * @param string $message The notification's message
     * @param string $title The notification's title
     * @param array $options
     */
    public function warning($text, $onlyNextRequest = false, array $options = [])
    {
        $theme = (isset($options['theme']) && $options['theme'] != '') ? $options['theme'] : 'metroui';
        $timeout = (isset($options['timeout']) && $options['timeout'] != '' && is_int($options['timeout'])) ? $options['timeout'] : 0;
        $layout = (isset($options['layout']) && $options['layout'] != '') ? $options['layout'] : 'topRight';
        if ($onlyNextRequest){
            $this->addForNextRequest($theme, $timeout, 'warning', $layout, $text, null, null);
            return;
        }
        $this->add($theme, $timeout, 'warning', $layout, $text, null, null, $onlyNextRequest);
    }

    /**
     * Shortcut for adding a success notification
     *
     * @param string $message The notification's message
     * @param string $title The notification's title
     * @param array $options
     */
    public function success($text, $onlyNextRequest = false, array $options = [])
    {
        $theme = (isset($options['theme']) && $options['theme'] != '') ? $options['theme'] : 'metroui';
        $timeout = (isset($options['timeout']) && $options['timeout'] != '' && is_int($options['timeout'])) ? $options['timeout'] : 0;
        $layout = (isset($options['layout']) && $options['layout'] != '') ? $options['layout'] : 'topRight';
        if ($onlyNextRequest){
            $this->addForNextRequest($theme, $timeout, 'success', $layout, $text, null, null);
            return;
        }
        $this->add($theme, $timeout, 'success', $layout, $text, null, null, $onlyNextRequest);
    }


    /**
     * Clear all notifications
     *
     * @param bool $withSession if true (default) clean notifications in session too.
     */
    public function clear()
    {
        $this->notifications = [];
    }

    public function clearFlashed(bool $withSession = true)
    {
        $this->flashedNotifications = [];

        if ($withSession) {
            $this->session->forget('laravel::flash-notifications');
        }
    }

    public function clearAll(bool $withSession = true)
    {
        $this->notifications = [];
        $this->flashedNotifications = [];

        if ($withSession) {
            $this->session->forget('laravel::flash-notifications');
        }
    }
}
