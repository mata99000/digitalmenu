<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\ItemOption;
use App\Models\OptionPrice;

class ItemOptionsSeeder extends Seeder
{
    public function run()
    {
        $item = Item::find(2); // Pretpostavljamo da već imate item s ID 1

        $options = $item->options()->createMany([
            ['name' => 'Bez sira', 'type' => 'remove'],
            ['name' => 'Dodaj bacon', 'type' => 'add'],
            ['name' => 'Extra luk', 'type' => 'add']
        ]);

        // Dodavanje cena za relevantne opcije
        foreach ($options as $option) {
            if ($option->name == 'Dodaj bacon') {
                OptionPrice::create([
                    'option_id' => $option->id,
                    'amount' => 50
                ]);
            }
            if ($option->name == 'Extra luk') {
                OptionPrice::create([
                    'option_id' => $option->id,
                    'amount' => 10
                ]);
            }
        }
    }
}