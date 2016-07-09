<?php

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            'Home & Garden',
            'Leisure',
            'Gifts',
            'Kids',
            'Technology',
            'Reading',
            'Automotive',
        ];
        foreach ($categories as $category)
        {
            $cat = Category::firstOrCreate(['title'=>$category,'slug'=>str_slug($category)]);
        }
    }
}
