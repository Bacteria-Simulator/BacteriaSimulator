<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class IterationZeroTest extends DuskTestCase
{
    public function testNewUserEmail()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/edit_account')
             ->type('example@example.com', 'email')
             ->press('Submit')
             ->seePageIs('/account')
             ->see('example@example.com');
         });
    }
    
    public function testUserPassword()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/edit_account')
            ->type('123abc', 'password')
            ->press('Submit')
            ->seePageIs('/account');
        });
    }
    
    public function testSubmitButton()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/edit_account')
             ->press('Submit')
             ->seePageIs('/account');
         });
    }

    public function testAccountPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/account')
             ->click('Account')
             ->seePageIs('/account');
         });
    }

    public function testEditAccountPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/account')
             ->click('Edit Account')
             ->seePageIs('/account_edit');
        });
    }

    public function testUserLogin()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
            ->type('email', '$user->email')
            ->press('login-button')
            ->assertPathIs('/home');
        });
    }

    public function testCreateAccount()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
            ->press('createaccountbutton')
            ->assertPathIs('/register')
            ->type('email', $user->email)
            ->type('password', secret)
            ->press('createbutton')
            ->assertPathIs('/login');
            //perform user login test (test 1)
        });
    }

    public function testNavigateToLoginPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/home')
            //check if login link is available
            ->clickLink('Login')
            ->assertPathIs('/login');
        });
    }

    public function testNavigateToCreateAccountPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/home')
            ->clickLink('Login')
            ->assertPathIs('/register')
            //if on login page check if create button is available
            ->press(‘createbutton’)
            ->assertPathIs('/createaccount');
        });
    }

    public function testAdminLogout()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/account')
            //check if logout button is available
            ->press('logout')
            //check if season is ended
            ->assertPathIs('/login');
        });
    }
}
