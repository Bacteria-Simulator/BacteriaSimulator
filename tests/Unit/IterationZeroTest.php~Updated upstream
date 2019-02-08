<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Brower\Pages\edit_account;

class IterationZeroTest extends TestCase
{

    public function testNewUserEmail()
    {
        $this->get('/')
             ->visit('/edit_account')
             ->type('example@example.com', 'email')
             ->press('Submit')
             ->seePageIs('/account')
             ->see("Email: example@example.com");
    }
    
    public function testUserPassword()
    {
        $this->visit('/edit_account')
            ->type('123abc', 'password')
            ->press('Submit')
            ->seePageIs('/account');
    }
    
    public function testSubmitButton()
    {
    	$this->get('/')
    	     ->visit('/edit_account')
             ->press('Submit')
             ->seePageIs('/account');
    }

    public function testAccountPage()
    {
        $this->visit('/account')
             ->click('Account')
             ->seePageIs('/account');
    }

    public function testEditAccountPage()
    {
        $this->visit('/account')
             ->click('Edit Account')
             ->seePageIs('/account_edit');
    }

    public function testUserLogin()
    {
        $this->visit('/login')
            ->type('email', '$user->email')
            ->press('login-button')
            ->assertPathIs('/home');
    }

    public function testCreateAccount()
    {
        $this->visit('/login')
            ->press('createaccountbutton')
            ->assertPathIs('/register')
            ->type('email', $user->email)
            ->type('password', secret)
            ->press('createbutton')
            ->assertPathIs('/login');
            //perform user login test (test 1)
    }

    public function testNavigateToLoginPage()
    {
        $this->visit('/home')
            //check if login link is available
            ->clickLink('Login')
            ->assertPathIs('/login');
    }

    public function testNavigateToCreateAccountPage()
    {
        $this->visit('/home')
            ->clickLink('Login')
            ->assertPathIs('/register')
            //if on login page check if create button is available
            ->press(‘createbutton’)
            ->assertPathIs('/createaccount');
    }

    public function testAdminLogout()
    {
        $this->visit('/account')
            //check if logout button is available
            ->press('logout')
            //check if season is ended
            ->assertPathIs('/login');
    }
}
