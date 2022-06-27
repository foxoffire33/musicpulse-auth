<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class NotificationsTest extends TestCase
{
    /**
     * Testen of een gebruiker een notificatie naar het device kan sturen
     */
    public function testSendAlertToDevice(){}

    /**
     * Testen of ik een alert naar alle devices kan sturen, gebruiker moet hiervoor Admin rechten hebben.
     */
    public function testSendAlertToAllDevice(){}

    /**
     * Tetsen of het lukt om een device aangemelden
     */
    public function testLoginDevice(){}

    /**
     * Testen of het lukt om 1 device afmelden
     */
    public function testLogoutDevice(){}

    /**
     * Testen of ik alle devices tegelijk kan uitloggen, gebruiker moet hiervoor Admin rechten hebben.
     */
    public function testLogoutAllDevices(){}

    /**
     * Testen of je playlist updates naar het device kan sturen
     */
    public function testRefreshPlaylistsOnDevice(){}
}
