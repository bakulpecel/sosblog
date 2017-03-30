<?php

use Phinx\Seed\AbstractSeed;

class Posts extends AbstractSeed
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
        $faker = Faker\Factory::create();

        for ($i = 0; $i < 10; $i++) { 
            $data[] = [
                'title'     => $faker->sentence,
                'content'   => $faker->text($maxNbChars = 2000),
                'user_id'   => rand(1, 5),
            ];
        }

        $post = $this->table('posts');
        $post->insert($data)
             ->save();
    }
}
