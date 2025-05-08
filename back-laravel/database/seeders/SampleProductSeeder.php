<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Collection;
use App\Models\Color;
use App\Models\DiscountProduct;

class SampleProductSeeder extends Seeder
{
    public function run(): void
    {
        $collection = Collection::firstOrCreate(['name' => 'Italiano']);
        $collection2 = Collection::firstOrCreate(['name' => 'Classic']);

        $colorOrange = Color::firstOrCreate([
            'name' => 'Orange',
            'hex_code' => '#FFA500'
        ]);

        $colorWhite = Color::firstOrCreate([
            'name' => 'White',
            'hex_code' => '#FFFFFF'
        ]);

        $colorGrey = Color::firstOrCreate([
            'name' => 'Grey',
            'hex_code' => '#808080'
        ]);

        $colorBlack = Color::firstOrCreate([
            'name' => 'Black',
            'hex_code' => '#000000'
        ]);

        $colorBeige = Color::firstOrCreate([
            'name' => 'Beige',
            'hex_code' => 'F5F5DC'
        ]);

        $colorGreen = Color::firstOrCreate([
            'name' => 'Green',
            'hex_code' => '#008000'
        ]);

        $colorYellow = Color::firstOrCreate([
            'name' => 'Yellow',
            'hex_code' => '#FFFF00'
        ]);

        $colorBlue = Color::firstOrCreate([
            'name' => 'Blue',
            'hex_code' => '#0000FF'
        ]);

        $colorPurple = Color::firstOrCreate([
            'name' => 'Purple',
            'hex_code' => '#A020F0'
        ]);

        $colorPink = Color::firstOrCreate([
            'name' => 'Pink',
            'hex_code' => '#FFC0CB'
        ]);

        $colorRed = Color::firstOrCreate([
            'name' => 'Red',
            'hex_code' => '#FF0000'
        ]);

        $colorMochaBrown = Color::firstOrCreate([
            'name' => 'Mocha Brown',
            'hex_code' => '#3F261D'
        ]);

        $colorLightGreen = Color::firstOrCreate([
            'name' => 'Light Green',
            'hex_code' => '#90EE90'
        ]);


        // 2
        $product = Product::create([
            'collection_id' => $collection->id,
            'name' => 'Brr Brr Patapim',
            'description' => <<<EOT
Bring a touch of magic to your wardrobe with this enchanting T-shirt featuring a whimsical fox straight out of a dream. With its vibrant personality and playful eyes, the fox invites you into a world of fun and imagination. The cheerful Italian phrase “Brr, brr patapim, il mio cappello è pieno di Slim!” adds an extra dose of charm and mystery—perfect for those who love a bit of flair in their fashion. Crafted by IgestShop, this T-shirt guarantees comfort, creativity, and character in every thread.

• Artistic design full of fantasy and humor
• Premium-quality cotton, soft and breathable
• Versatile and comfortable for daily wear or gifting
EOT,
            'price' => 39.99,
            'final_price' => 39.99,
            'is_discount' => false,
            'category' => 'tshirt',
            'gender' => 'unisex',
        ]);


        $product->images()->createMany([
            [
                'image_url' => 'Brr-Brr-Patapim-white-back.png',
                'color_id' => $colorWhite->id,
                'is_main' => false,
            ],
            [
                'image_url' => 'Brr-Brr-Patapim-white-front.png',
                'color_id' => $colorWhite->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'Brr-Brr-Patapim-yellow-back.png',
                'color_id' => $colorYellow->id,
                'is_main' => false,
            ],
            [
                'image_url' => 'Brr-Brr-Patapim-yellow-front.png',
                'color_id' => $colorYellow->id,
                'is_main' => true,
            ],
        ]);

        $variants = [
            ['size' => 'S', 'color_id' => $colorYellow->id, 'amount' => 10],
            ['size' => 'M', 'color_id' => $colorYellow->id, 'amount' => 5],
            ['size' => 'L', 'color_id' => $colorYellow->id, 'amount' => 3],
            ['size' => 'S', 'color_id' => $colorWhite->id, 'amount' => 5],
            ['size' => 'M', 'color_id' => $colorWhite->id, 'amount' => 4],
            ['size' => 'XL', 'color_id' => $colorWhite->id, 'amount' => 3],
        ];

        foreach ($variants as $variant) {
            $product->variants()->create($variant);
        }

        $this->command->info('Sample product created successfully.');

        // 3
        $product = Product::create([
            'collection_id' => $collection->id,
            'name' => 'Capuchino Assassino',
            'description' => <<<EOT
Step into stealth mode with the "Capuchino Assassino" T-shirt – where coffee culture meets killer style. Designed for those who take their espresso with a side of mystery, this shirt features a sleek, modern design and a bold rear slogan in dark roast brown: “CAPUCHINO ASSASSINO.” Whether you’re conquering your daily grind or slipping through the shadows of the city, this tee has your back. With high-quality cotton and a relaxed fit, it's made for coffee addicts, streetwear fans, and subtle assassins of fashion alike.

• Striking text-based design with clean aesthetic
• Soft, breathable fabric for all-day comfort
• Ideal for urban explorers, baristas, or anime-styled ninja vibes
EOT,
            'price' => 49.99,
            'final_price' => 49.99,
            'category' => 'tshirt',
            'gender' => 'unisex',
        ]);


        $product->images()->createMany([
            [
                'image_url' => 'Capuchino-Assassino-white-back.png',
                'color_id' => $colorWhite->id,
                'is_main' => false,
            ],
            [
                'image_url' => 'Capuchino-Assassino-white-front.png',
                'color_id' => $colorWhite->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'Capuchino-Assassino-blue-back.png',
                'color_id' => $colorBlue->id,
                'is_main' => false,
            ],
            [
                'image_url' => 'Capuchino-Assassino-blue-front.png',
                'color_id' => $colorBlue->id,
                'is_main' => true,
            ],
        ]);

        $variants = [
            ['size' => 'S', 'color_id' => $colorBlue->id, 'amount' => 10],
            ['size' => 'M', 'color_id' => $colorBlue->id, 'amount' => 5],
            ['size' => 'L', 'color_id' => $colorBlue->id, 'amount' => 3],
            ['size' => 'S', 'color_id' => $colorWhite->id, 'amount' => 5],
            ['size' => 'M', 'color_id' => $colorWhite->id, 'amount' => 4],
            ['size' => 'XL', 'color_id' => $colorWhite->id, 'amount' => 3],
        ];

        foreach ($variants as $variant) {
            $product->variants()->create($variant);
        }

        $this->command->info('Sample product created successfully.');

        // 4
        $product = Product::create([
            'collection_id' => $collection->id,
            'name' => 'Lirili rilira lila',
            'description' => <<<EOT
Let your personality sing with the vibrant “Lirili rilira lila” T-shirt – a wearable melody of color, humor, and self-expression. The bold text on the back, “Lirili rili ralila,” dances with rhythm and quirk, making this tee a standout piece in any wardrobe. Crafted from 100% soft cotton with a timeless cut, this shirt blends comfort with creativity. Ideal for free spirits, art lovers, and those who love to wear their mood out loud.

• Whimsical back print for a fun, artsy vibe
• Soft, breathable cotton for everyday wear
• Eye-catching color options and discounted price make it a must-have
EOT,
            'price' => 39.99,
            'final_price' => 19.99,
            'is_discount' => true,
            'category' => 'tshirt',
            'gender' => 'unisex',
        ]);

        $product->images()->createMany([
            [
                'image_url' => 'Lirili-rili-ralila-yellow-back.png',
                'color_id' => $colorYellow->id,
                'is_main' => false,
            ],
            [
                'image_url' => 'Lirili-rili-ralila-yellow-front.png',
                'color_id' => $colorYellow->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'Lirili-rili-ralila-white-back.png',
                'color_id' => $colorWhite->id,
                'is_main' => false,
            ],
            [
                'image_url' => 'Lirili-rili-ralila-white-front.png',
                'color_id' => $colorWhite->id,
                'is_main' => true,
            ],
        ]);

        $variants = [
            ['size' => 'S', 'color_id' => $colorYellow->id, 'amount' => 10],
            ['size' => 'M', 'color_id' => $colorYellow->id, 'amount' => 5],
            ['size' => 'L', 'color_id' => $colorYellow->id, 'amount' => 3],
            ['size' => 'S', 'color_id' => $colorWhite->id, 'amount' => 5],
            ['size' => 'M', 'color_id' => $colorWhite->id, 'amount' => 4],
            ['size' => 'XL', 'color_id' => $colorWhite->id, 'amount' => 3],
        ];

        foreach ($variants as $variant) {
            $product->variants()->create($variant);
        }

        DiscountProduct::updateOrCreate(
            ['product_id' => $product->id],
            [
                'new_price' => 19.99,
                'date_start' => now()->subDays(1),
                'date_end' => now()->addDays(10),
            ]
        );

        $this->command->info('Sample product created successfully.');



        // 5
        $product = Product::create([
            'collection_id' => $collection->id,
            'name' => 'Tripi Tropi',
            'description' => <<<EOT
Embrace the bizarre and the brilliant with our “Tripi Tropi” T-shirt — a surreal blend of a curious cat and a shrimp, brought together in a whimsical underwater fantasy. Whether you're a lover of quirky fashion or just someone who enjoys standing out, this tee offers the perfect balance of humor, art, and imagination.

Crafted from soft, breathable cotton with a timeless cut and durable stitching, it's available in both bold black and clean white. The signature “IgestShop” logo at the hem seals the deal for fans of authentic, original fashion.

• Playful and imaginative design for dreamers and trendsetters
• Premium cotton ensures day-long comfort and wearability
• Limited-time offer at an unbeatable price – grab yours before it swims away!
EOT,
            'price' => 39.99,
            'final_price' => 9.99,
            'is_discount' => true,
            'category' => 'tshirt',
            'gender' => 'unisex',
        ]);

        $product->images()->createMany([
            [
                'image_url' => 'Tripi-Tropi-black-back.png',
                'color_id' => $colorBlack->id,
                'is_main' => false,
            ],
            [
                'image_url' => 'Tripi-Tropi-black-front.png',
                'color_id' => $colorBlack->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'Tripi-Tropi-white-back.png',
                'color_id' => $colorWhite->id,
                'is_main' => false,
            ],
            [
                'image_url' => 'Tripi-Tropi-white-front.png',
                'color_id' => $colorWhite->id,
                'is_main' => true,
            ],
        ]);

        $variants = [
            ['size' => 'S', 'color_id' => $colorBlack->id, 'amount' => 10],
            ['size' => 'M', 'color_id' => $colorBlack->id, 'amount' => 5],
            ['size' => 'L', 'color_id' => $colorBlack->id, 'amount' => 3],
            ['size' => 'S', 'color_id' => $colorWhite->id, 'amount' => 5],
            ['size' => 'M', 'color_id' => $colorWhite->id, 'amount' => 4],
            ['size' => 'XL', 'color_id' => $colorWhite->id, 'amount' => 3],
        ];

        foreach ($variants as $variant) {
            $product->variants()->create($variant);
        }

        DiscountProduct::updateOrCreate(
            ['product_id' => $product->id],
            [
                'new_price' => 9.99,
                'date_start' => now()->subDays(1),
                'date_end' => now()->addDays(10),
            ]
        );

        $this->command->info('Sample product created successfully.');

        // Ballerina-Cappucina-purple-back
        // 6
        $product = Product::create([
            'collection_id' => $collection->id,
            'name' => 'Ballerina Cappucina',
            'description' => <<<EOT
Whirl into a dream where espresso meets elegance with our “Ballerina Cappuccina” T-shirt – a tribute to grace, caffeine, and imagination. This delightful design features a poised ballerina whose head is a cappuccino cup, complete with swirling latte art – a surreal blend of performance and passion. Above the artwork, the iconic IgestShop logo adds a bold signature touch.

On the back, the whimsical phrase “Ballerina Cappuccina” reminds you to dance through life with style and flavor. Made from ultra-soft, breathable cotton, this tee is perfect for those who love expressive fashion, coffee culture, and a splash of the unexpected.

• Original front-and-back print with artistic flair
• Soft, durable cotton for day-long comfort
• For coffee dreamers, creative spirits, and trendsetters
EOT,
            'price' => 39.99,
            'final_price' => 9.99,
            'is_discount' => true,
            'category' => 'tshirt',
            'gender' => 'unisex',
        ]);

        $product->images()->createMany([
            [
                'image_url' => 'Ballerina-Cappucina-purple-back.png',
                'color_id' => $colorPurple->id,
                'is_main' => false,
            ],
            [
                'image_url' => 'Ballerina-Cappucina-purple-front.png',
                'color_id' => $colorPurple->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'Ballerina-Cappucina-white-back.png',
                'color_id' => $colorWhite->id,
                'is_main' => false,
            ],
            [
                'image_url' => 'Ballerina-Cappucina-white-front.png',
                'color_id' => $colorWhite->id,
                'is_main' => true,
            ],
        ]);

        $variants = [
            ['size' => 'S', 'color_id' => $colorPurple->id, 'amount' => 10],
            ['size' => 'M', 'color_id' => $colorPurple->id, 'amount' => 5],
            ['size' => 'L', 'color_id' => $colorPurple->id, 'amount' => 3],
            ['size' => 'S', 'color_id' => $colorWhite->id, 'amount' => 5],
            ['size' => 'M', 'color_id' => $colorWhite->id, 'amount' => 4],
            ['size' => 'XL', 'color_id' => $colorWhite->id, 'amount' => 3],
        ];

        foreach ($variants as $variant) {
            $product->variants()->create($variant);
        }

        DiscountProduct::updateOrCreate(
            ['product_id' => $product->id],
            [
                'new_price' => 9.99,
                'date_start' => now()->subDays(1),
                'date_end' => now()->addDays(10),
            ]
        );

        $this->command->info('Sample product created successfully.');

        // 7
        $product = Product::create([
            'collection_id' => $collection->id,
            'name' => 'Tung Tung Tung Sagur',
            'description' => <<<EOT
Get ready to vibe with the rhythm of style in our “Tung Tung Tung Sagur” T-shirt – a playful fusion of beats, color, and bold energy. This eye-catching design brings to life a musical spirit that feels both tribal and futuristic, perfect for those who march to the beat of their own drum. Whether you're dancing in the city or chilling in nature, this tee makes a loud statement in the best way.

Crafted from premium cotton and featuring the IgestShop seal of quality, it offers all-day comfort and standout appeal in vibrant yellow and energetic green colorways.

• Unique rhythmic-inspired name and design
• Soft, durable fabric for freedom of movement
• Perfect for artists, free-thinkers, and bold dressers
EOT,
            'price' => 49.99,
            'final_price' => 49.99,
            'is_discount' => false,
            'category' => 'tshirt',
            'gender' => 'unisex',
        ]);

        $product->images()->createMany([
            [
                'image_url' => 'tung_tung_tung_sagur-green-back.png',
                'color_id' => $colorGreen->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'tung_tung_tung_sagur-green-front.png',
                'color_id' => $colorGreen->id,
                'is_main' => false,
            ],
            [
                'image_url' => 'tung_tung_tung_sagur-yellow-back.png',
                'color_id' => $colorYellow->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'tung_tung_tung_sagur-yellow-front.png',
                'color_id' => $colorYellow->id,
                'is_main' => false,
            ],
        ]);

        $variants = [
            ['size' => 'S', 'color_id' => $colorGreen->id, 'amount' => 10],
            ['size' => 'M', 'color_id' => $colorGreen->id, 'amount' => 5],
            ['size' => 'L', 'color_id' => $colorGreen->id, 'amount' => 3],
            ['size' => 'S', 'color_id' => $colorYellow->id, 'amount' => 5],
            ['size' => 'M', 'color_id' => $colorYellow->id, 'amount' => 4],
            ['size' => 'XL', 'color_id' => $colorYellow->id, 'amount' => 3],
        ];

        foreach ($variants as $variant) {
            $product->variants()->create($variant);
        }

        $this->command->info('Sample product created successfully.');

        // 8
        // Gangster Footera
        $product = Product::create([
            'collection_id' => $collection->id,
            'name' => 'Gangster Footer',
            'description' => <<<EOT
Bring street style to life with the bold and fearless “Gangster Footer” T-shirt – a tribute to the raw edge of urban culture with a playful twist. Featuring a minimalistic yet powerful design, this shirt embodies swagger, confidence, and unapologetic originality. The phrase “Gangster Footer” is printed with pride, letting you walk your own path – with attitude.

Made from soft, high-quality cotton and available in clean white and bright yellow, this unisex tee offers style and comfort in perfect balance. Whether you're kicking back or making moves, this shirt’s got your back.

• Bold streetwear design with urban flair
• Soft, breathable fabric made to move with you
• Ideal for style rebels, trendsetters, and those who walk their own way
EOT,
            'price' => 49.99,
            'final_price' => 49.99,
            'is_discount' => false,
            'category' => 'tshirt',
            'gender' => 'unisex',
        ]);

        $product->images()->createMany([
            [
                'image_url' => 'gangster_footer-white-back.png',
                'color_id' => $colorWhite->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'gangster_footer-white-front.png',
                'color_id' => $colorWhite->id,
                'is_main' => false,
            ],
            [
                'image_url' => 'gangster_footer-yellow-back.png',
                'color_id' => $colorYellow->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'gangster_footer-yellow-front.png',
                'color_id' => $colorYellow->id,
                'is_main' => false,
            ],
        ]);

        $variants = [
            ['size' => 'S', 'color_id' => $colorWhite->id, 'amount' => 10],
            ['size' => 'M', 'color_id' => $colorWhite->id, 'amount' => 5],
            ['size' => 'L', 'color_id' => $colorWhite->id, 'amount' => 3],
            ['size' => 'S', 'color_id' => $colorYellow->id, 'amount' => 5],
            ['size' => 'M', 'color_id' => $colorYellow->id, 'amount' => 4],
            ['size' => 'XL', 'color_id' => $colorYellow->id, 'amount' => 3],
        ];

        foreach ($variants as $variant) {
            $product->variants()->create($variant);
        }

        $this->command->info('Sample product created successfully.');

        // 9
        // Bobritto bandito
        $product = Product::create([
            'collection_id' => $collection->id,
            'name' => 'Bobritto bandito',
            'description' => <<<EOT
Meet the outlaw of your wardrobe – the “Bobritto Bandito” T-shirt. With a name as bold as its style, this tee captures the spirit of rebellion, wrapped in charm and wrapped again like a burrito on the run. Featuring a quirky design and a comfortable cut, it’s perfect for those who break rules (fashion or otherwise) with a grin.

Crafted from soft, high-quality cotton and available in stealthy grey and crisp white, this tee is your go-to for casual mischief, lazy Sundays, or late-night adventures.

• Fun, rebellious theme with a street-smart twist
• Durable and breathable cotton – built for bandits on the move
• Perfect for those who bring flavor and fire to their fashion
EOT,
            'price' => 49.99,
            'final_price' => 49.99,
            'is_discount' => false,
            'category' => 'tshirt',
            'gender' => 'unisex',
        ]);

        $product->images()->createMany([
            [
                'image_url' => 'Bomborito_bandito-grey-back.png',
                'color_id' => $colorGrey->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'Bomborito_bandito-grey-front.png',
                'color_id' => $colorGrey->id,
                'is_main' => false,
            ],
            [
                'image_url' => 'Bomborito_bandito-white-back.png',
                'color_id' => $colorWhite->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'Bomborito_bandito-white-back.png',
                'color_id' => $colorWhite->id,
                'is_main' => false,
            ],
        ]);

        $variants = [
            ['size' => 'S', 'color_id' => $colorWhite->id, 'amount' => 10],
            ['size' => 'M', 'color_id' => $colorWhite->id, 'amount' => 5],
            ['size' => 'L', 'color_id' => $colorWhite->id, 'amount' => 3],
            ['size' => 'S', 'color_id' => $colorGrey->id, 'amount' => 5],
            ['size' => 'M', 'color_id' => $colorGrey->id, 'amount' => 4],
            ['size' => 'XL', 'color_id' => $colorGrey->id, 'amount' => 3],
        ];

        foreach ($variants as $variant) {
            $product->variants()->create($variant);
        }

        $this->command->info('Sample product created successfully.');

        // bombardino crocodilo
        // 10
        $product = Product::create([
            'collection_id' => $collection->id,
            'name' => 'Bombardino Crocodilo',
            'description' => <<<EOT
Unleash your wild side with the “Bombardino Crocodilo” T-shirt – a bold fusion of explosive energy and swampy swagger. Whether you're storming the streets or lounging like a king of the jungle, this tee is made to roar. The name alone turns heads, and the design backs it up with fierce attitude and a hint of tropical chaos.

Crafted from premium cotton for all-day comfort, and available in smooth grey and crisp white, this piece brings edge and humor into one iconic look. Be the crocodile in a world of ducks.

• Fearless name with a playful, untamed vibe
• Soft cotton build with lasting comfort and quality
• Perfect for bold personalities, wild hearts, and street-savvy style
EOT,
            'price' => 49.99,
            'final_price' => 49.99,
            'is_discount' => false,
            'category' => 'tshirt',
            'gender' => 'unisex',
        ]);

        $product->images()->createMany([
            [
                'image_url' => 'bombardiro_crocodilo-grey-back.png',
                'color_id' => $colorGrey->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'bombardiro_crocodilo-grey-front.png',
                'color_id' => $colorGrey->id,
                'is_main' => false,
            ],
            [
                'image_url' => 'bombardiro_crocodilo-white-back.png',
                'color_id' => $colorWhite->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'bombardiro_crocodilo-white-front.png',
                'color_id' => $colorWhite->id,
                'is_main' => false,
            ],
        ]);

        $variants = [
            ['size' => 'S', 'color_id' => $colorWhite->id, 'amount' => 10],
            ['size' => 'M', 'color_id' => $colorWhite->id, 'amount' => 5],
            ['size' => 'L', 'color_id' => $colorWhite->id, 'amount' => 3],
            ['size' => 'S', 'color_id' => $colorGrey->id, 'amount' => 5],
            ['size' => 'M', 'color_id' => $colorGrey->id, 'amount' => 4],
            ['size' => 'XL', 'color_id' => $colorGrey->id, 'amount' => 3],
        ];

        foreach ($variants as $variant) {
            $product->variants()->create($variant);
        }

        $this->command->info('Sample product created successfully.');

        // 11

        $product = Product::create([
            'collection_id' => $collection2->id,
            'name' => 'Hoodie 1',
            'description' => <<<EOT
Level up your comfort game with “Hoodie 1” — the ultimate fusion of minimal design and maximum coziness. Crafted for everyday wear, this hoodie wraps you in warmth while keeping your street-style edge sharp. Its clean silhouette and premium cotton blend make it a timeless essential in any wardrobe.

Whether you're layering up for a chilly day or just embracing laid-back vibes, this hoodie is built for versatility, durability, and effortless cool. The neutral tone makes it easy to pair with anything from joggers to denim.

• Classic fit with adjustable drawstring hood and front pocket
• Soft, insulating fabric for all-day warmth
• Perfect for casual outings, workouts, or cozy nights in
EOT,

            'price' => 69.99,
            'final_price' => 69.99,
            'is_discount' => false,
            'category' => 'hoodie',
            'gender' => 'male',
        ]);

        $product->images()->createMany([
            [
                'image_url' => 'hoodie_1_white_back.png',
                'color_id' => $colorGrey->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'hoodie_1_white_front.png',
                'color_id' => $colorGrey->id,
                'is_main' => false,
            ],
        ]);

        $variants = [
            ['size' => 'XS', 'color_id' => $colorGrey->id, 'amount' => 5],
            ['size' => 'M', 'color_id' => $colorGrey->id, 'amount' => 4],
            ['size' => 'XL', 'color_id' => $colorGrey->id, 'amount' => 3],
        ];

        foreach ($variants as $variant) {
            $product->variants()->create($variant);
        }

        $this->command->info('Sample product created successfully.');

        // 12
        $product = Product::create([
            'collection_id' => $collection2->id,
            'name' => 'Hoodie 2',
            'description' => <<<EOT
Gear up with confidence in our sleek and rugged “Hoodie 2” – built for movement, made for comfort. Designed with modern minimalism in mind, this hoodie brings together function and form in one sharp package. Whether you're hitting the gym, the street, or the sofa, it's the reliable go-to layer every man needs in his rotation.

Crafted from a thick, soft cotton blend and structured for durability, it features a spacious hood, clean lines, and a fit that balances comfort with edge. It’s more than just a hoodie – it’s your everyday armor.

• Durable fabric with a smooth, brushed interior for extra warmth
• Functional design with classic front pocket and adjustable hood
• Clean grey tone makes it versatile and easy to style
EOT,

            'price' => 69.99,
            'final_price' => 69.99,
            'is_discount' => false,
            'category' => 'hoodie',
            'gender' => 'male',
        ]);

        $product->images()->createMany([
            [
                'image_url' => 'hoodie_2_white_back.png',
                'color_id' => $colorGrey->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'hoodie_2_white_front.png',
                'color_id' => $colorGrey->id,
                'is_main' => false,
            ],
        ]);

        $variants = [
            ['size' => 'S', 'color_id' => $colorGrey->id, 'amount' => 5],
            ['size' => 'M', 'color_id' => $colorGrey->id, 'amount' => 4],
            ['size' => 'XL', 'color_id' => $colorGrey->id, 'amount' => 3],
        ];

        foreach ($variants as $variant) {
            $product->variants()->create($variant);
        }

        $this->command->info('Sample product created successfully.');

        // hoodie_3_black_front 13
        $product = Product::create([
            'collection_id' => $collection2->id,
            'name' => 'Hoodie 3',
            'description' => <<<EOT
Command attention in every color with “Hoodie 3” – a bold statement piece that blends comfort, character, and versatility. Whether you're keeping it classic in black, bringing heat with orange, or going soft and stylish in beige, this hoodie is designed to match your energy. Built with a premium cotton blend, it offers a cozy fit, a clean silhouette, and street-ready attitude.

From morning hustle to late-night chill, “Hoodie 3” adapts to your vibe without compromising on warmth or edge. A must-have for guys who know how to switch it up and stay sharp.

• Warm fleece interior with classic fit and front pocket
• Designed for confidence, built for comfort
EOT,

            'price' => 69.99,
            'final_price' => 69.99,
            'is_discount' => false,
            'category' => 'hoodie',
            'gender' => 'male',
        ]);

        $product->images()->createMany([
            [
                'image_url' => 'hoodie_3_black_back.png',
                'color_id' => $colorGrey->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'hoodie_3_black_front.png',
                'color_id' => $colorGrey->id,
                'is_main' => false,
            ],
            [
                'image_url' => 'hoodie_3_orange_back.png',
                'color_id' => $colorOrange->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'hoodie_3_orange_front.png',
                'color_id' => $colorOrange->id,
                'is_main' => false,
            ],
            [
                'image_url' => 'hoodie_3_beige_back.png',
                'color_id' => $colorBeige->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'hoodie_3_beige_front.png',
                'color_id' => $colorBeige->id,
                'is_main' => false,
            ],
        ]);
        // beige

        $variants = [
            ['size' => 'S', 'color_id' => $colorBlack->id, 'amount' => 5],
            ['size' => 'M', 'color_id' => $colorBlack->id, 'amount' => 4],
            ['size' => 'XL', 'color_id' => $colorBlack->id, 'amount' => 3],
            ['size' => 'S', 'color_id' => $colorOrange->id, 'amount' => 5],
            ['size' => 'M', 'color_id' => $colorOrange->id, 'amount' => 4],
            ['size' => 'XL', 'color_id' => $colorOrange->id, 'amount' => 3],
            ['size' => 'S', 'color_id' => $colorBeige->id, 'amount' => 5],
            ['size' => 'M', 'color_id' => $colorBeige->id, 'amount' => 4],
            ['size' => 'XL', 'color_id' => $colorBeige->id, 'amount' => 3],
        ];

        foreach ($variants as $variant) {
            $product->variants()->create($variant);
        }

        $this->command->info('Sample product created successfully.');

        // 14
        $product = Product::create([
            'collection_id' => $collection2->id,
            'name' => 'Hoodie 4',
            'description' => <<<EOT
Stay sharp, stay grounded with “Hoodie 4” — a clean and confident look for everyday movement. Featuring a deep green tone and minimalist design, this hoodie is perfect for those who like their style calm, cool, and collected. Whether you're out in the city or unwinding at home, this piece delivers both function and finesse.

Crafted from premium cotton blend fabric, it offers a relaxed fit, breathable warmth, and classic details like a kangaroo pocket and adjustable drawstring hood.

• Comfortable fabric blend for all-day wear
• Great for layering, solo styling, or casual outings
EOT,

            'price' => 69.99,
            'final_price' => 69.99,
            'is_discount' => false,
            'category' => 'hoodie',
            'gender' => 'male',
        ]);

        $product->images()->createMany([
            [
                'image_url' => 'hoodie_4_green_back.png',
                'color_id' => $colorGreen->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'hoodie_4_green_front.png',
                'color_id' => $colorGreen->id,
                'is_main' => false,
            ],
        ]);
        // beige

        $variants = [
            ['size' => 'S', 'color_id' => $colorGreen->id, 'amount' => 5],
            ['size' => 'M', 'color_id' => $colorGreen->id, 'amount' => 4],
            ['size' => 'XL', 'color_id' => $colorGreen->id, 'amount' => 3],
        ];

        foreach ($variants as $variant) {
            $product->variants()->create($variant);
        }

        $this->command->info('Sample product created successfully.');

        // 15
        $product = Product::create([
            'collection_id' => $collection2->id,
            'name' => 'Hoodie 6',
            'description' => <<<EOT
Keep it classic, keep it cool with “Hoodie 6” – a sleek black essential that’s all about understated power and all-day comfort. Whether you're layering it under a jacket or wearing it solo, this hoodie makes a bold, no-fuss statement. Designed for men who appreciate minimalist style with maximum impact.

Made from soft, high-quality fabric, it features a relaxed fit, durable stitching, and an adjustable drawstring hood. A staple in any wardrobe, this hoodie pairs effortlessly with jeans, joggers, or streetwear fits.

• Soft cotton blend for warmth and comfort
• Built for everyday wear – from work to weekend
EOT,

            'price' => 69.99,
            'final_price' => 69.99,
            'is_discount' => false,
            'category' => 'hoodie',
            'gender' => 'male',
        ]);

        $product->images()->createMany([
            [
                'image_url' => 'hoodie_6_black_back.png',
                'color_id' => $colorBlack->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'hoodie_6_black_front.png',
                'color_id' => $colorBlack->id,
                'is_main' => false,
            ],
        ]);

        $variants = [
            ['size' => 'S', 'color_id' => $colorBlack->id, 'amount' => 5],
            ['size' => 'M', 'color_id' => $colorBlack->id, 'amount' => 4],
            ['size' => 'XL', 'color_id' => $colorBlack->id, 'amount' => 3],
        ];

        foreach ($variants as $variant) {
            $product->variants()->create($variant);
        }

        $this->command->info('Sample product created successfully.');

        // 16
        $product = Product::create([
            'collection_id' => $collection2->id,
            'name' => 'Hoodie 7',
            'description' => <<<EOT
Make a bold yet laid-back impression with “Hoodie 7” – a deep blue essential made for those who move with quiet confidence. This hoodie brings cool tones and clean design into one everyday staple, combining style and comfort without trying too hard.

Whether you're heading out for a casual day or relaxing indoors, the soft cotton blend and classic fit offer warmth, flexibility, and ease. It's simple, it's strong, and it belongs in every man's lineup.

• Soft fleece interior with drawstring hood and front pocket
• Ideal for transitional weather, daily wear, and layering
EOT,

            'price' => 69.99,
            'final_price' => 69.99,
            'is_discount' => false,
            'category' => 'hoodie',
            'gender' => 'male',
        ]);

        $product->images()->createMany([
            [
                'image_url' => 'hoodie_7_blue_back.png',
                'color_id' => $colorBlue->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'hoodie_7_blue_front.png',
                'color_id' => $colorBlue->id,
                'is_main' => false,
            ],
        ]);

        $variants = [
            ['size' => 'S', 'color_id' => $colorBlue->id, 'amount' => 5],
            ['size' => 'M', 'color_id' => $colorBlue->id, 'amount' => 4],
            ['size' => 'XL', 'color_id' => $colorBlue->id, 'amount' => 3],
        ];

        foreach ($variants as $variant) {
            $product->variants()->create($variant);
        }

        $this->command->info('Sample product created successfully.');

        // 17
        $product = Product::create([
            'collection_id' => $collection2->id,
            'name' => 'Hoodie 8',
            'description' => <<<EOT
Own the streets in “Hoodie 8” — a pure black essential built for effortless edge and all-day comfort. With its clean silhouette and confident tone, this hoodie speaks volumes without saying a word. Whether you're layering up or keeping it simple, it delivers style, warmth, and durability in every stitch.

Designed with a relaxed fit and crafted from premium cotton-blend fabric, it features a classic hood, front kangaroo pocket, and reinforced seams — because basics should never feel basic.

• Ultra-soft fabric for comfort and mobility
• Your go-to piece for everyday wear or off-duty looks
EOT,

            'price' => 69.99,
            'final_price' => 69.99,
            'is_discount' => false,
            'category' => 'hoodie',
            'gender' => 'male',
        ]);

        $product->images()->createMany([
            [
                'image_url' => 'hoodie_8_black_back.png',
                'color_id' => $colorBlack->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'hoodie_8_black_front.png',
                'color_id' => $colorBlack->id,
                'is_main' => false,
            ],
        ]);

        $variants = [
            ['size' => 'S', 'color_id' => $colorBlack->id, 'amount' => 5],
            ['size' => 'M', 'color_id' => $colorBlack->id, 'amount' => 4],
            ['size' => 'XL', 'color_id' => $colorBlack->id, 'amount' => 3],
        ];

        foreach ($variants as $variant) {
            $product->variants()->create($variant);
        }

        $this->command->info('Sample product created successfully.');

        // 17
        $product = Product::create([
            'collection_id' => $collection2->id,
            'name' => 'Hoodie 9',
            'description' => <<<EOT
Break expectations and own your look with “Hoodie 9” – a powerful pink statement piece for the bold and unapologetic. Designed for men who aren’t afraid to add color to their confidence, this hoodie blends streetwear energy with everyday comfort.

Made from premium cotton-blend fabric, it delivers warmth, flexibility, and a standout silhouette. Whether you're hitting the city or just chilling in style, this hoodie turns heads without trying too hard.

• Soft fleece interior and structured fit for comfort and durability
• A fearless essential for fashion-forward men
EOT,

            'price' => 69.99,
            'final_price' => 69.99,
            'is_discount' => false,
            'category' => 'hoodie',
            'gender' => 'male',
        ]);

        $product->images()->createMany([
            [
                'image_url' => 'hoodie_9_pink_back.png',
                'color_id' => $colorPink->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'hoodie_9_pink_front.png',
                'color_id' => $colorPink->id,
                'is_main' => false,
            ],
        ]);

        $variants = [
            ['size' => 'S', 'color_id' => $colorPink->id, 'amount' => 5],
            ['size' => 'M', 'color_id' => $colorPink->id, 'amount' => 4],
            ['size' => 'XL', 'color_id' => $colorPink->id, 'amount' => 3],
        ];

        foreach ($variants as $variant) {
            $product->variants()->create($variant);
        }

        $this->command->info('Sample product created successfully.');

        // 18
        $product = Product::create([
            'collection_id' => $collection2->id,
            'name' => 'Hoodie 10',
            'description' => <<<EOT
Fuel your wardrobe with fire in “Hoodie 10” – a bold red essential made to turn up the heat. This hoodie isn't just a layer – it’s a statement. With its vibrant color and clean design, it delivers confidence, energy, and comfort in every stitch.

Crafted from high-quality cotton-blend fabric, it’s built for durability, movement, and style. Whether you're powering through your day or laying low in laid-back fashion, “Hoodie 10” keeps your look strong and sharp.

• Cozy fleece interior with a structured hood and kangaroo pocket
• Built for bold personalities and everyday wear
EOT,
            'price' => 69.99,
            'final_price' => 69.99,
            'is_discount' => false,
            'category' => 'hoodie',
            'gender' => 'male',
        ]);

        $product->images()->createMany([
            [
                'image_url' => 'hoodie_10_red_back.png',
                'color_id' => $colorRed->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'hoodie_10_red_front.png',
                'color_id' => $colorRed->id,
                'is_main' => false,
            ],
        ]);

        $variants = [
            ['size' => 'S', 'color_id' => $colorRed->id, 'amount' => 5],
            ['size' => 'M', 'color_id' => $colorRed->id, 'amount' => 4],
            ['size' => 'XL', 'color_id' => $colorRed->id, 'amount' => 3],
        ];

        foreach ($variants as $variant) {
            $product->variants()->create($variant);
        }

        $this->command->info('Sample product created successfully.');

        // 19
        $product = Product::create([
            'collection_id' => $collection2->id,
            'name' => 'Hoodie 11',
            'description' => <<<EOT
Bright, bold, and made to move — “Hoodie 11” brings fire to your fit with its vibrant orange hue and confident design. Created for women who love color and comfort, this hoodie blends soft textures with striking energy. It’s more than cozy — it’s a fashion statement.

With a relaxed fit, premium cotton blend, and practical features like a drawstring hood and kangaroo pocket, it’s built to keep up with your rhythm, whether you're on the go or off duty.

• Soft fleece interior for warmth and all-day comfort
• Designed for women who lead with bold style
EOT,

            'price' => 69.99,
            'final_price' => 69.99,
            'is_discount' => false,
            'category' => 'hoodie',
            'gender' => 'female',
        ]);

        $product->images()->createMany([
            [
                'image_url' => 'hoodie_11_orange_back.png',
                'color_id' => $colorOrange->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'hoodie_11_orange_front.png',
                'color_id' => $colorOrange->id,
                'is_main' => false,
            ],
        ]);

        $variants = [
            ['size' => 'S', 'color_id' => $colorOrange->id, 'amount' => 5],
            ['size' => 'M', 'color_id' => $colorOrange->id, 'amount' => 4],
            ['size' => 'XL', 'color_id' => $colorOrange->id, 'amount' => 3],
        ];

        foreach ($variants as $variant) {
            $product->variants()->create($variant);
        }

        $this->command->info('Sample product created successfully.');

        // 20
        $product = Product::create([
            'collection_id' => $collection2->id,
            'name' => 'Hoodie 12',
            'description' => <<<EOT
Cool, confident, and effortlessly chic — “Hoodie 12” is made for women who bring calm power to every step. Wrapped in a soft blue tone and crafted from a premium cotton blend, this hoodie delivers relaxed comfort and understated style in one perfect piece.

Ideal for layering, lounging, or stepping out, its cozy fleece interior, adjustable hood, and spacious pocket make it a must-have for every wardrobe.

• Soft blue hue for a calm yet striking look
• Fleece-lined for warmth and comfort
• Designed to move with you — from slow mornings to bold adventures
EOT,

            'price' => 69.99,
            'final_price' => 69.99,
            'is_discount' => false,
            'category' => 'hoodie',
            'gender' => 'female',
        ]);

        $product->images()->createMany([
            [
                'image_url' => 'hoodie_12_blue_back.png',
                'color_id' => $colorBlue->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'hoodie_12_blue_front.png',
                'color_id' => $colorBlue->id,
                'is_main' => false,
            ],
        ]);

        $variants = [
            ['size' => 'S', 'color_id' => $colorBlue->id, 'amount' => 5],
            ['size' => 'M', 'color_id' => $colorBlue->id, 'amount' => 4],
            ['size' => 'XL', 'color_id' => $colorBlue->id, 'amount' => 3],
        ];

        foreach ($variants as $variant) {
            $product->variants()->create($variant);
        }

        $this->command->info('Sample product created successfully.');

        // 21
        $product = Product::create([
            'collection_id' => $collection2->id,
            'name' => 'Hoodie 12',
            'description' => <<<EOT
Embrace elegance with a bold twist in “Hoodie 13” – a rich purple essential for women who mix comfort with confidence. With its deep color and cozy fabric, this hoodie is perfect for those who love to stand out while staying grounded in warmth and style.

Made with a soft fleece interior and premium cotton blend, it’s designed for daily wear — whether you’re heading to a café, campus, or cozy evening in.

• Regal purple tone for a standout feminine look
• Comfortable fit with a spacious front pocket and hood
• Made for bold women who love everyday style with personality
EOT,

            'price' => 69.99,
            'final_price' => 69.99,
            'is_discount' => false,
            'category' => 'hoodie',
            'gender' => 'female',
        ]);

        $product->images()->createMany([
            [
                'image_url' => 'hoodie_13_purple_back.png',
                'color_id' => $colorPurple->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'hoodie_13_purple_front.png',
                'color_id' => $colorPurple->id,
                'is_main' => false,
            ],
        ]);

        $variants = [
            ['size' => 'S', 'color_id' => $colorPurple->id, 'amount' => 5],
            ['size' => 'M', 'color_id' => $colorPurple->id, 'amount' => 4],
            ['size' => 'XL', 'color_id' => $colorPurple->id, 'amount' => 3],
        ];

        foreach ($variants as $variant) {
            $product->variants()->create($variant);
        }

        $this->command->info('Sample product created successfully.');

        // 22
        $product = Product::create([
            'collection_id' => $collection2->id,
            'name' => 'Frigo Camelo',
            'description' => <<<EOT
Cool as a fridge, wild as a camel — the “Frigo Camelo” hoodie is where surreal design meets street-style comfort. With a playful name and clean white aesthetic, this hoodie is perfect for women who love to blend humor, fashion, and a little chaos into their daily wear.

Crafted from soft cotton fabric and designed for a flattering, cozy fit, it's great for chilly mornings, quirky outfits, or just making a statement that’s uniquely yours.

• Soft fleece lining for comfort and warmth
• Perfect for creative souls, humor lovers, and bold dressers
EOT,
            'price' => 15.99,
            'final_price' => 15.99,
            'is_discount' => false,
            'category' => 'hoodie',
            'gender' => 'female',
        ]);

        $product->images()->createMany([
            [
                'image_url' => 'frigo_camelo_white_back.png',
                'color_id' => $colorWhite->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'frigo_camelo_white_white.png',
                'color_id' => $colorWhite->id,
                'is_main' => false,
            ],
        ]);

        $variants = [
            ['size' => 'XS', 'color_id' => $colorWhite->id, 'amount' => 5],
            ['size' => 'S', 'color_id' => $colorWhite->id, 'amount' => 5],
            ['size' => 'M', 'color_id' => $colorWhite->id, 'amount' => 4],
            ['size' => 'L', 'color_id' => $colorWhite->id, 'amount' => 3],
            ['size' => 'XL', 'color_id' => $colorWhite->id, 'amount' => 3],
            ['size' => 'XXL', 'color_id' => $colorWhite->id, 'amount' => 2],
        ];

        foreach ($variants as $variant) {
            $product->variants()->create($variant);
        }

        $this->command->info('Sample product created successfully.');

        // 23
        $product = Product::create([
            'collection_id' => $collection2->id,
            'name' => 'La Vaca Saturno Saturnite',
            'description' => <<<EOT
Prepare for liftoff with “La Vaca Saturno Saturnite” — a hoodie that’s part streetwear, part intergalactic art piece. With a name inspired by cosmic cows and planetary orbits, this design delivers surreal humor and bold originality, making it a standout piece for style astronauts and dreamers alike.

Crafted in soft white fabric with a clean silhouette, it offers all-day comfort while launching your look into orbit. Perfect for those who like their fashion with a side of science fiction and style.

• Out-of-this-world name, down-to-earth comfort
• Soft cotton blend with cozy interior
• Ideal for creative minds, sci-fi lovers, and fashion adventurers
EOT,
            'price' => 43.99,
            'final_price' => 43.99,
            'is_discount' => false,
            'category' => 'hoodie',
            'gender' => 'male',
        ]);

        $product->images()->createMany([
            [
                'image_url' => 'La Vaca Saturno Saturnite_white_back.png',
                'color_id' => $colorWhite->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'La Vaca Saturno Saturnite_white_front.png',
                'color_id' => $colorWhite->id,
                'is_main' => false,
            ],
        ]);

        $variants = [
            ['size' => 'XS', 'color_id' => $colorWhite->id, 'amount' => 5],
            ['size' => 'L', 'color_id' => $colorWhite->id, 'amount' => 3],
            ['size' => 'XXL', 'color_id' => $colorWhite->id, 'amount' => 2],
        ];

        foreach ($variants as $variant) {
            $product->variants()->create($variant);
        }

        $this->command->info('Sample product created successfully.');

        // 24
        $product = Product::create([
            'collection_id' => $collection2->id,
            'name' => 'Brri Brri Bicus Dicus Bombicus',
            'description' => <<<EOT
Turn up the volume on your style with “Brri Brri Bicus Dicus Bombicus” — a hoodie that sounds like a beat drop and feels like pure confidence. Designed for women who love rhythm, color, and standing out, this vibrant hoodie is bursting with personality. With yellow and orange options that radiate energy, it's your go-to for lighting up any room.

Made from soft cotton blend fabric, it’s as cozy as it is eye-catching. Whether you’re dancing through the streets or just feeling loud in your lounge, this piece is made to move with you.

• Loud name, louder color palette – unapologetically bold
• Cozy fleece lining and relaxed fit for all-day energy
• Perfect for creative rebels and color-loving queens
EOT,
            'price' => 43.99,
            'final_price' => 43.99,
            'is_discount' => false,
            'category' => 'hoodie',
            'gender' => 'female',
        ]);

        $product->images()->createMany([
            [
                'image_url' => 'Brri Brri Bicus Dicus Bombicus_yellow_back.png',
                'color_id' => $colorYellow->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'Brri Brri Bicus Dicus Bombicus_yellow_front.png',
                'color_id' => $colorYellow->id,
                'is_main' => false,
            ],
            [
                'image_url' => 'Brri Brri Bicus Dicus Bombicus_orange_back.png',
                'color_id' => $colorOrange->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'Brri Brri Bicus Dicus Bombicus_orange_front.png',
                'color_id' => $colorOrange->id,
                'is_main' => false,
            ],
        ]);

        $variants = [
            ['size' => 'XS', 'color_id' => $colorYellow->id, 'amount' => 5],
            ['size' => 'L', 'color_id' => $colorYellow->id, 'amount' => 3],
            ['size' => 'XXL', 'color_id' => $colorYellow->id, 'amount' => 2],
            ['size' => 'S', 'color_id' => $colorOrange->id, 'amount' => 5],
            ['size' => 'M', 'color_id' => $colorOrange->id, 'amount' => 3],
            ['size' => 'XL', 'color_id' => $colorOrange->id, 'amount' => 2],
        ];

        foreach ($variants as $variant) {
            $product->variants()->create($variant);
        }

        $this->command->info('Sample product created successfully.');

        // 25
        $product = Product::create([
            'collection_id' => $collection2->id,
            'name' => 'Chimpansini Capuchini',
            'description' => <<<EOT
Swing into smooth style with “Chimpansini Capuchini” — a hoodie brewed for comfort and flavored with fun. Whether you’re monkeying around or sipping something strong, this piece delivers café culture with jungle energy. The name alone sets the tone, and the rich colorways (crisp white and mocha brown) do the rest.

Crafted from a high-quality cotton blend, this hoodie brings together warmth, durability, and that wild-caffeinated edge. Ideal for laid-back legends and espresso-fueled adventurers alike.

• Comfy fleece interior and roomy fit
• For coffee lovers, fun-seekers, and style-swingers
EOT,
            'price' => 53.99,
            'final_price' => 53.99,
            'is_discount' => false,
            'category' => 'hoodie',
            'gender' => 'male',
        ]);

        $product->images()->createMany([
            [
                'image_url' => 'Chimpansini Capuchini_white_back.png',
                'color_id' => $colorWhite->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'Chimpansini Capuchini_white_front.png',
                'color_id' => $colorWhite->id,
                'is_main' => false,
            ],
            [
                'image_url' => 'Chimpansini Capuchini_mocha_brown_back.png',
                'color_id' => $colorMochaBrown->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'Chimpansini Capuchini_mocha_brown_front.png',
                'color_id' => $colorMochaBrown->id,
                'is_main' => false,
            ],
        ]);

        $variants = [
            ['size' => 'XS', 'color_id' => $colorWhite->id, 'amount' => 5],
            ['size' => 'L', 'color_id' => $colorMochaBrown->id, 'amount' => 3],
            ['size' => 'S', 'color_id' => $colorMochaBrown->id, 'amount' => 5],
            ['size' => 'M', 'color_id' => $colorMochaBrown->id, 'amount' => 3],
            ['size' => 'XL', 'color_id' => $colorMochaBrown->id, 'amount' => 2],
        ];

        foreach ($variants as $variant) {
            $product->variants()->create($variant);
        }

        $this->command->info('Sample product created successfully.');

        // 26
        $product = Product::create([
            'collection_id' => $collection2->id,
            'name' => 'Gorillo Watermellondrillo',
            'description' => <<<EOT
Unleash the wild and juicy with “Gorillo Watermellondrillo” — a hoodie that punches through the ordinary with tropical power and primate swagger. Blending gorilla strength with watermelon cool, this piece is made for those who turn sidewalks into jungles and outfits into statements.

With its refreshing light green color and a unisex cut, it's perfect for anyone who dares to be different. Premium quality meets playful design — ideal for streetwear lovers, fruit fanatics, and style beasts.

• Soft cotton blend and cozy fleece interior
• Made for trendsetters, party starters, and bold personalities
EOT,
            'price' => 83.99,
            'final_price' => 83.99,
            'is_discount' => false,
            'category' => 'hoodie',
            'gender' => 'unisex',
        ]);

        $product->images()->createMany([
            [
                'image_url' => 'Gorillo Watermellondrillo_light_green_back.png',
                'color_id' => $colorLightGreen->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'Gorillo Watermellondrillo_light_green_front.png',
                'color_id' => $colorLightGreen->id,
                'is_main' => false,
            ]
        ]);

        $variants = [
            ['size' => 'XS', 'color_id' => $colorLightGreen->id, 'amount' => 5],
            ['size' => 'L', 'color_id' => $colorLightGreen->id, 'amount' => 3],
            ['size' => 'XL', 'color_id' => $colorLightGreen->id, 'amount' => 2],
        ];

        foreach ($variants as $variant) {
            $product->variants()->create($variant);
        }

        $this->command->info('Sample product created successfully.');


        // 27
        $product = Product::create([
            'collection_id' => $collection->id,
            'name' => 'T-shirt 1',
            'description' => <<<EOT
Meet your new go-to essential: “T-shirt 1” from IgestShop — a clean, no-fuss staple made for effortless everyday wear. Available in classic black and white, plus bold orange and cool blue, this tee adapts to your vibe, whether you're layering it up or wearing it solo.

Crafted from soft, breathable cotton and cut with a regular fit, it delivers the comfort you want and the quality you expect. Ideal for casual days, gym sessions, or weekend adventures.

• High-quality cotton for long-lasting comfort
• A foundational piece for any modern wardrobe
EOT,
            'price' => 23.99,
            'final_price' => 23.99,
            'is_discount' => false,
            'category' => 'tshirt',
            'gender' => 'male',
        ]);

        $product->images()->createMany([
            [
                'image_url' => 'tshirt1-black-back.png',
                'color_id' => $colorBlack->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'tshirt1-black-front.png',
                'color_id' => $colorBlack->id,
                'is_main' => false,
            ],
            [
                'image_url' => 'tshirt1-white-back.png',
                'color_id' => $colorWhite->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'tshirt1-white-front.png',
                'color_id' => $colorWhite->id,
                'is_main' => false,
            ],
            [
                'image_url' => 'tshirt1-orange-back.png',
                'color_id' => $colorOrange->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'tshirt1-orange-front.png',
                'color_id' => $colorOrange->id,
                'is_main' => false,
            ],
            [
                'image_url' => 'tshirt1-blue-back.png',
                'color_id' => $colorBlue->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'tshirt1-blue-front.png',
                'color_id' => $colorBlue->id,
                'is_main' => false,
            ],
        ]);

        $variants = [
            ['size' => 'XS', 'color_id' => $colorBlack->id, 'amount' => 5],
            ['size' => 'L', 'color_id' => $colorBlack->id, 'amount' => 3],
            ['size' => 'XL', 'color_id' => $colorBlack->id, 'amount' => 2],
            ['size' => 'XL', 'color_id' => $colorWhite->id, 'amount' => 2],
            ['size' => 'M', 'color_id' => $colorWhite->id, 'amount' => 7],
            ['size' => 'S', 'color_id' => $colorOrange->id, 'amount' => 10],
            ['size' => 'XS', 'color_id' => $colorOrange->id, 'amount' => 10],
            ['size' => 'L', 'color_id' => $colorBlue->id, 'amount' => 6],
            ['size' => 'XXL', 'color_id' => $colorBlue->id, 'amount' => 3],
        ];

        foreach ($variants as $variant) {
            $product->variants()->create($variant);
        }

        $this->command->info('Sample product created successfully.');


        // 28
        $product = Product::create([
            'collection_id' => $collection->id,
            'name' => 'T-shirt 2',
            'description' => <<<EOT
Redefine everyday fashion with “T-shirt 2” — a high-end essential for women who blend confidence with creativity. This elevated tee pairs premium materials with bold style, available in classic black, clean white, and standout orange to match your mood and message.

Crafted for superior comfort and lasting shape, this piece is made to be noticed — whether you’re dressing it up with layers or keeping it effortlessly cool. It’s more than a T-shirt — it’s your signature.

• Luxe cotton blend for smooth feel and structure
• Eye-catching color options for versatile styling
• Ideal for bold wardrobes, streetwear statements, or minimalist outfits
EOT,
            'price' => 83.99,
            'final_price' => 83.99,
            'is_discount' => false,
            'category' => 'tshirt',
            'gender' => 'female',
        ]);

        $product->images()->createMany([
            [
                'image_url' => 'tshirt2-black-back.png',
                'color_id' => $colorBlack->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'tshirt2-black-front.png',
                'color_id' => $colorBlack->id,
                'is_main' => false,
            ],
            [
                'image_url' => 'tshirt2-white-back.png',
                'color_id' => $colorWhite->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'tshirt2-white-front.png',
                'color_id' => $colorWhite->id,
                'is_main' => false,
            ],
            [
                'image_url' => 'tshirt2-orange-back.png',
                'color_id' => $colorOrange->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'tshirt2-orange-front.png',
                'color_id' => $colorOrange->id,
                'is_main' => false,
            ]
        ]);

        $variants = [
            ['size' => 'XS', 'color_id' => $colorBlack->id, 'amount' => 5],
            ['size' => 'L', 'color_id' => $colorBlack->id, 'amount' => 3],
            ['size' => 'XL', 'color_id' => $colorBlack->id, 'amount' => 2],
            ['size' => 'XXL', 'color_id' => $colorWhite->id, 'amount' => 2],
            ['size' => 'M', 'color_id' => $colorWhite->id, 'amount' => 7],
            ['size' => 'S', 'color_id' => $colorOrange->id, 'amount' => 10],
            ['size' => 'XS', 'color_id' => $colorOrange->id, 'amount' => 10],
        ];

        foreach ($variants as $variant) {
            $product->variants()->create($variant);
        }

        $this->command->info('Sample product created successfully.');

        // 29
        $product = Product::create([
            'collection_id' => $collection->id,
            'name' => 'T-shirt 3',
            'description' => <<<EOT
Elevate your everyday essentials with “T-shirt 3” — a refined piece designed for men who value premium quality and timeless style. With a sleek color palette of deep black and cool blue, this tee delivers minimalist energy with maximum impact.

Crafted from soft, durable fabric and tailored for a flattering fit, it’s perfect for pairing with your favorite jeans, layering under jackets, or making a strong solo statement. It’s comfort, confidence, and clean design — all in one.

• Neutral colorways for effortless styling
• Designed for modern men who lead with subtle strength
EOT,
            'price' => 73.99,
            'final_price' => 73.99,
            'is_discount' => false,
            'category' => 'tshirt',
            'gender' => 'male',
        ]);

        $product->images()->createMany([
            [
                'image_url' => 'tshirt3-black-back.png',
                'color_id' => $colorBlack->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'tshirt3-black-front.png',
                'color_id' => $colorBlack->id,
                'is_main' => false,
            ],
            [
                'image_url' => 'tshirt3-blue-back.png',
                'color_id' => $colorBlue->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'tshirt2-blue-front.png',
                'color_id' => $colorBlue->id,
                'is_main' => false,
            ]
        ]);

        $variants = [
            ['size' => 'XS', 'color_id' => $colorBlack->id, 'amount' => 5],
            ['size' => 'L', 'color_id' => $colorBlack->id, 'amount' => 3],
            ['size' => 'XXL', 'color_id' => $colorBlue->id, 'amount' => 2],
            ['size' => 'M', 'color_id' => $colorBlue->id, 'amount' => 7],
        ];

        foreach ($variants as $variant) {
            $product->variants()->create($variant);
        }

        $this->command->info('Sample product created successfully.');

        // 30
        $product = Product::create([
            'collection_id' => $collection->id,
            'name' => 'T-shirt 4',
            'description' => <<<EOT
Keep it classic with a fresh twist in “T-shirt 4” — a versatile staple crafted for women who blend simplicity with sharp style. Available in timeless black and crisp white, this tee is all about effortless elegance and comfort you can count on.

Made from high-quality cotton fabric, it features a flattering fit and soft feel, perfect for layering, dressing up, or staying cool and casual. It’s the kind of piece that works with everything — and elevates anything.

• Soft, breathable fabric for a flattering silhouette
• Perfect for minimalist looks, daily wear, or subtle street styling
EOT,
            'price' => 43.99,
            'final_price' => 43.99,
            'is_discount' => false,
            'category' => 'tshirt',
            'gender' => 'female',
        ]);

        $product->images()->createMany([
            [
                'image_url' => 'tshirt4-black-back.png',
                'color_id' => $colorBlack->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'tshirt4-black-front.png',
                'color_id' => $colorBlack->id,
                'is_main' => false,
            ],
            [
                'image_url' => 'tshirt4-white-back.png',
                'color_id' => $colorWhite->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'tshirt4-white-front.png',
                'color_id' => $colorWhite->id,
                'is_main' => false,
            ]
        ]);

        $variants = [
            ['size' => 'XS', 'color_id' => $colorBlack->id, 'amount' => 5],
            ['size' => 'L', 'color_id' => $colorBlack->id, 'amount' => 3],
            ['size' => 'XXL', 'color_id' => $colorWhite->id, 'amount' => 2],
            ['size' => 'S', 'color_id' => $colorWhite->id, 'amount' => 10],
            ['size' => 'M', 'color_id' => $colorWhite->id, 'amount' => 7],
        ];

        foreach ($variants as $variant) {
            $product->variants()->create($variant);
        }

        $this->command->info('Sample product created successfully.');

        // 31
        $product = Product::create([
            'collection_id' => $collection->id,
            'name' => 'T-shirt 5',
            'description' => <<<EOT
Keep it bold, simple, and super affordable with “T-shirt 5” — the everyday essential that doesn’t compromise on style. Made for guys who like easy choices and bold colors, this tee comes in energetic orange and cool blue, perfect for casual days and laid-back vibes.

Whether you're hitting the park, chilling at home, or grabbing coffee with friends, this shirt has your back — literally. With a soft cotton feel and classic fit, it’s your budget-friendly go-to for comfort and color.

• Ultra-soft cotton for relaxed all-day wear
• A steal at this price — stock up while it lasts
EOT,

            'price' => 3.99,
            'final_price' => 3.99,
            'is_discount' => false,
            'category' => 'tshirt',
            'gender' => 'male',
        ]);

        $product->images()->createMany([
            [
                'image_url' => 'tshirt5-orange-back.png',
                'color_id' => $colorOrange->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'tshirt5-orange-front.png',
                'color_id' => $colorOrange->id,
                'is_main' => false,
            ],
            [
                'image_url' => 'tshirt5-blue-back.png',
                'color_id' => $colorBlue->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'tshirt5-blue-front.png',
                'color_id' => $colorBlue->id,
                'is_main' => false,
            ]
        ]);

        $variants = [
            ['size' => 'XS', 'color_id' => $colorBlue->id, 'amount' => 5],
            ['size' => 'L', 'color_id' => $colorBlue->id, 'amount' => 3],
            ['size' => 'XXL', 'color_id' => $colorOrange->id, 'amount' => 2],
            ['size' => 'S', 'color_id' => $colorOrange->id, 'amount' => 10],
            ['size' => 'M', 'color_id' => $colorOrange->id, 'amount' => 7],
        ];

        foreach ($variants as $variant) {
            $product->variants()->create($variant);
        }

        $this->command->info('Sample product created successfully.');
    }
}

