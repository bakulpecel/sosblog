<?php

use Phinx\Seed\AbstractSeed;

class Roles extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
    {
        $data = [
            ['roles' => 'Administrator'],
            ['roles' => 'User']
        ];
        
        $post = $this->table('roles');
        $post->insert($data)
             ->save();
    }
    }
}
