<?php

namespace Uasoft\Badaso\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Console\Input\InputOption;
use Uasoft\Badaso\Models\Role;
use Uasoft\Badaso\Models\User;
use Uasoft\Badaso\Models\UserRole;

class AdminCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'badaso:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make sure there is a user with the admin role that has all of the necessary permissions.';

    /**
     * Get user options.
     */
    protected function getOptions()
    {
        return [
            ['create', null, InputOption::VALUE_NONE, 'Create an admin user', null],
            ['name', null, InputOption::VALUE_REQUIRED, 'Name of the user', null],
            ['username', null, InputOption::VALUE_REQUIRED, 'Username of the user', null],
            ['password', null, InputOption::VALUE_REQUIRED, 'Password of the user', null],
            ['confirm_password', null, InputOption::VALUE_REQUIRED, 'Confirmation password', null],
        ];
    }

    public function fire()
    {
        return $this->handle();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // Get or create user
        $user = $this->getUser(
            $this->option('create')
        );

        // the user not returned
        if (! $user) {
            $this->info('User not found.');
            exit;
        }

        $this->info('The user now has full access to your site.');
    }

    /**
     * Get command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['email', InputOption::VALUE_REQUIRED, 'The email of the user.', null],
        ];
    }

    /**
     * Get the administrator role, create it if it does not exists.
     *
     * @return mixed
     */
    protected function getAdministratorRole()
    {
        $role = Role::where('name', 'administrator')->first();

        if (is_null($role)) {
            $role = new Role();
            $role->name = 'administrator';
            $role->display_name = 'Administrator';
            $role->save();
        }

        return $role;
    }

    /**
     * Get or create user.
     *
     * @param  bool  $create
     * @return \App\User
     */
    protected function getUser($create = false)
    {
        $email = $this->argument('email');
        $name = $this->option('name');
        $username = $this->option('username');
        $password = $this->option('password');
        $confirmPassword = $this->option('confirm_password');

        // If we need to create a new user go ahead and create it
        if ($create) {
            if (! $name) {
                $name = $this->ask('Enter the admin name');
            }

            if (! $username) {
                $username = $this->ask('Enter the admin username (lowercase)');
            }

            if (! $password) {
                $password = $this->secret('Enter admin password');
            }

            if (! $confirmPassword) {
                $confirmPassword = $this->secret('Confirm Password');
            }

            // Ask for email if there wasnt set one
            if (! $email) {
                $email = $this->ask('Enter the admin email');
            }

            // Passwords don't match
            if ($password != $confirmPassword) {
                $this->info("Passwords don't match");

                return;
            }

            $this->info('Creating admin account');

            $user = new User();
            $user->name = $name;
            $user->username = $username;
            $user->email = $email;
            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->password = Hash::make($password);
            $user->save();

            $role = $this->getAdministratorRole();

            $user_role = new UserRole();
            $user_role->user_id = $user->id;
            $user_role->role_id = $role->id;
            $user_role->save();

            return $user;
        } else {
            $user = User::where('email', $email)->first();

            return $user;
        }
    }
}
