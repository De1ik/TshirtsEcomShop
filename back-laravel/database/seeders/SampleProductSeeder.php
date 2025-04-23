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


        // 2
        // Создаем продукт
        $product = Product::create([
            'collection_id' => $collection->id,
            'name' => 'Brr Brr Patapim',
            'description' => <<<EOT
This T-shirt is decorated with a fairy-tale character, a fox, who combines the features of nature and fantasy, creating an atmosphere of magic and good humour. The Italian inscription ‘Brr, brr patapim, il mio cappello è pieno di Slim!’ adds charm and mystery, while the IgestShop brand at the bottom emphasises quality and style.
High-quality print with detailed image
Soft, pleasant to the body cotton
Perfect for everyday wear or as a gift
EOT,
            'price' => 39.99,
            'final_price' => 39.99,
            'is_discount' => false,
            'category' => 'tshirt',
            'gender' => 'unisex',
        ]);


        // Добавим изображения
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

        // Добавим варианты (размер, цвет, количество)
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
Unleash your inner ninja with our bold "Capuchino Assassino" T-shirt. This premium white tee features a clean, minimalist back print with the fierce slogan "CAPUCHINO ASSASSINO" in dark brown, standing out against the crisp white cotton fabric. Perfect for fans of unique streetwear, coffee lovers with attitude, or anyone looking to make a powerful style statement. Crafted for comfort and durability, this shirt is ideal for casual wear, anime conventions, or just showing off your sharp taste.
EOT,
            'price' => 49.99,
            'final_price' => 49.99,
            'category' => 'tshirt',
            'gender' => 'unisex',
        ]);


        // Добавим изображения
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

        // Добавим варианты (размер, цвет, количество)
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
        // Создаем продукт
        $product = Product::create([
            'collection_id' => $collection->id,
            'name' => 'Lirili rilira lila',
            'description' => <<<EOT
This bright T-shirt stands out with its creative design and unforgettable style. On the back of the T-shirt, the bold and clear phrase "Lirili rili ralila" adds a playful and artistic touch. Made from 100% cotton, the T-shirt features a classic fit with short sleeves and a crew neckline, ensuring everyday comfort. It’s a perfect choice for those who value individuality, humor, and modern art in their daily wear.
EOT,
            'price' => 39.99,
            'final_price' => 19.99,
            'is_discount' => true,
            'category' => 'tshirt',
            'gender' => 'unisex',
        ]);

        // Добавим изображения
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

        // Добавим варианты (размер, цвет, количество)
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
        // Создаем продукт
        $product = Product::create([
            'collection_id' => $collection->id,
            'name' => 'Tripi Tropi',
            'description' => <<<EOT
Dive into a world of fantasy with our exclusive "Tripi Tropi" T-shirt. This unique design features a magical combination of a cat and a shrimp in an underwater style — an image that’s sure to turn heads. The print is applied to a high-quality black T-shirt made from soft, breathable fabric, ensuring comfort in any season.

At the bottom, a stylish "IgestShop" inscription highlights the brand's originality. The T-shirt features a classic cut with a crew neckline and double stitching for durability.
An ideal choice for those who love unconventional pieces, appreciate humor, and want to stand out from the crowd.
EOT,
            'price' => 39.99,
            'final_price' => 9.99,
            'is_discount' => true,
            'category' => 'tshirt',
            'gender' => 'unisex',
        ]);

        // Добавим изображения
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

        // Добавим варианты (размер, цвет, количество)
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
Step into a world of elegance and whimsy with our exclusive "Ballerina Cappuccina" T-shirt from IgestShop. This vibrant tee features a stunning front print of a graceful ballerina with a cappuccino cup for a head—complete with beautifully detailed latte art. Above the design, our IgestShop logo is proudly displayed in bold white font.
Turn the shirt around, and you'll find the phrase “Ballerina Cappuccina” stylishly printed across the upper back, celebrating the charm and uniqueness of this limited-edition piece. Crafted from high-quality, soft cotton fabric, this T-shirt offers comfort and style in equal measure—perfect for creative souls, coffee lovers, and fashion-forward individuals.
EOT,
            'price' => 39.99,
            'final_price' => 9.99,
            'is_discount' => true,
            'category' => 'tshirt',
            'gender' => 'unisex',
        ]);

        // Добавим изображения
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

        // Добавим варианты (размер, цвет, количество)
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
Step into a world of elegance and whimsy with our exclusive "Ballerina Cappuccina" T-shirt from IgestShop. This vibrant tee features a stunning front print of a graceful ballerina with a cappuccino cup for a head—complete with beautifully detailed latte art. Above the design, our IgestShop logo is proudly displayed in bold white font.
Turn the shirt around, and you'll find the phrase “Ballerina Cappuccina” stylishly printed across the upper back, celebrating the charm and uniqueness of this limited-edition piece. Crafted from high-quality, soft cotton fabric, this T-shirt offers comfort and style in equal measure—perfect for creative souls, coffee lovers, and fashion-forward individuals.
EOT,
            'price' => 49.99,
            'final_price' => 49.99,
            'is_discount' => false,
            'category' => 'tshirt',
            'gender' => 'unisex',
        ]);

        // Добавим изображения
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

        // Добавим варианты (размер, цвет, количество)
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
Step into a world of elegance and whimsy with our exclusive "Ballerina Cappuccina" T-shirt from IgestShop. This vibrant tee features a stunning front print of a graceful ballerina with a cappuccino cup for a head—complete with beautifully detailed latte art. Above the design, our IgestShop logo is proudly displayed in bold white font.
Turn the shirt around, and you'll find the phrase “Ballerina Cappuccina” stylishly printed across the upper back, celebrating the charm and uniqueness of this limited-edition piece. Crafted from high-quality, soft cotton fabric, this T-shirt offers comfort and style in equal measure—perfect for creative souls, coffee lovers, and fashion-forward individuals.
EOT,
            'price' => 49.99,
            'final_price' => 49.99,
            'is_discount' => false,
            'category' => 'tshirt',
            'gender' => 'unisex',
        ]);

        // Добавим изображения
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

        // Добавим варианты (размер, цвет, количество)
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
Step into a world of elegance and whimsy with our exclusive "Ballerina Cappuccina" T-shirt from IgestShop. This vibrant tee features a stunning front print of a graceful ballerina with a cappuccino cup for a head—complete with beautifully detailed latte art. Above the design, our IgestShop logo is proudly displayed in bold white font.
Turn the shirt around, and you'll find the phrase “Ballerina Cappuccina” stylishly printed across the upper back, celebrating the charm and uniqueness of this limited-edition piece. Crafted from high-quality, soft cotton fabric, this T-shirt offers comfort and style in equal measure—perfect for creative souls, coffee lovers, and fashion-forward individuals.
EOT,
            'price' => 49.99,
            'final_price' => 49.99,
            'is_discount' => false,
            'category' => 'tshirt',
            'gender' => 'unisex',
        ]);

        // Добавим изображения
        $product->images()->createMany([
            [
                'image_url' => 'Bomborito_bandito-gray-back.png',
                'color_id' => $colorGrey->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'Bomborito_bandito-gray-front.png',
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

        // Добавим варианты (размер, цвет, количество)
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
Step into a world of elegance and whimsy with our exclusive "Ballerina Cappuccina" T-shirt from IgestShop. This vibrant tee features a stunning front print of a graceful ballerina with a cappuccino cup for a head—complete with beautifully detailed latte art. Above the design, our IgestShop logo is proudly displayed in bold white font.
Turn the shirt around, and you'll find the phrase “Ballerina Cappuccina” stylishly printed across the upper back, celebrating the charm and uniqueness of this limited-edition piece. Crafted from high-quality, soft cotton fabric, this T-shirt offers comfort and style in equal measure—perfect for creative souls, coffee lovers, and fashion-forward individuals.
EOT,
            'price' => 49.99,
            'final_price' => 49.99,
            'is_discount' => false,
            'category' => 'tshirt',
            'gender' => 'unisex',
        ]);

        // Добавим изображения
        $product->images()->createMany([
            [
                'image_url' => 'bombardiro_crocodilo-gray-back.png',
                'color_id' => $colorGrey->id,
                'is_main' => true,
            ],
            [
                'image_url' => 'bombardiro_crocodilo-gray-front.png',
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

        // Добавим варианты (размер, цвет, количество)
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
Step into a world of elegance and whimsy with our exclusive "Ballerina Cappuccina" T-shirt from IgestShop. This vibrant tee features a stunning front print of a graceful ballerina with a cappuccino cup for a head—complete with beautifully detailed latte art. Above the design, our IgestShop logo is proudly displayed in bold white font.
Turn the shirt around, and you'll find the phrase “Ballerina Cappuccina” stylishly printed across the upper back, celebrating the charm and uniqueness of this limited-edition piece. Crafted from high-quality, soft cotton fabric, this T-shirt offers comfort and style in equal measure—perfect for creative souls, coffee lovers, and fashion-forward individuals.
EOT,
            'price' => 69.99,
            'final_price' => 69.99,
            'is_discount' => false,
            'category' => 'hoodie',
            'gender' => 'male',
        ]);

        // Добавим изображения
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

        // Добавим варианты (размер, цвет, количество)
        $variants = [
            ['size' => 'S', 'color_id' => $colorGrey->id, 'amount' => 5],
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
Step into a world of elegance and whimsy with our exclusive "Ballerina Cappuccina" T-shirt from IgestShop. This vibrant tee features a stunning front print of a graceful ballerina with a cappuccino cup for a head—complete with beautifully detailed latte art. Above the design, our IgestShop logo is proudly displayed in bold white font.
Turn the shirt around, and you'll find the phrase “Ballerina Cappuccina” stylishly printed across the upper back, celebrating the charm and uniqueness of this limited-edition piece. Crafted from high-quality, soft cotton fabric, this T-shirt offers comfort and style in equal measure—perfect for creative souls, coffee lovers, and fashion-forward individuals.
EOT,
            'price' => 69.99,
            'final_price' => 69.99,
            'is_discount' => false,
            'category' => 'hoodie',
            'gender' => 'male',
        ]);

        // Добавим изображения
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

        // Добавим варианты (размер, цвет, количество)
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
Step into a world of elegance and whimsy with our exclusive "Ballerina Cappuccina" T-shirt from IgestShop. This vibrant tee features a stunning front print of a graceful ballerina with a cappuccino cup for a head—complete with beautifully detailed latte art. Above the design, our IgestShop logo is proudly displayed in bold white font.
Turn the shirt around, and you'll find the phrase “Ballerina Cappuccina” stylishly printed across the upper back, celebrating the charm and uniqueness of this limited-edition piece. Crafted from high-quality, soft cotton fabric, this T-shirt offers comfort and style in equal measure—perfect for creative souls, coffee lovers, and fashion-forward individuals.
EOT,
            'price' => 69.99,
            'final_price' => 69.99,
            'is_discount' => false,
            'category' => 'hoodie',
            'gender' => 'male',
        ]);

        // Добавим изображения
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

        // Добавим варианты (размер, цвет, количество)
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
Step into a world of elegance and whimsy with our exclusive "Ballerina Cappuccina" T-shirt from IgestShop. This vibrant tee features a stunning front print of a graceful ballerina with a cappuccino cup for a head—complete with beautifully detailed latte art. Above the design, our IgestShop logo is proudly displayed in bold white font.
Turn the shirt around, and you'll find the phrase “Ballerina Cappuccina” stylishly printed across the upper back, celebrating the charm and uniqueness of this limited-edition piece. Crafted from high-quality, soft cotton fabric, this T-shirt offers comfort and style in equal measure—perfect for creative souls, coffee lovers, and fashion-forward individuals.
EOT,
            'price' => 69.99,
            'final_price' => 69.99,
            'is_discount' => false,
            'category' => 'hoodie',
            'gender' => 'male',
        ]);

        // Добавим изображения
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

        // Добавим варианты (размер, цвет, количество)
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
Step into a world of elegance and whimsy with our exclusive "Ballerina Cappuccina" T-shirt from IgestShop. This vibrant tee features a stunning front print of a graceful ballerina with a cappuccino cup for a head—complete with beautifully detailed latte art. Above the design, our IgestShop logo is proudly displayed in bold white font.
Turn the shirt around, and you'll find the phrase “Ballerina Cappuccina” stylishly printed across the upper back, celebrating the charm and uniqueness of this limited-edition piece. Crafted from high-quality, soft cotton fabric, this T-shirt offers comfort and style in equal measure—perfect for creative souls, coffee lovers, and fashion-forward individuals.
EOT,
            'price' => 69.99,
            'final_price' => 69.99,
            'is_discount' => false,
            'category' => 'hoodie',
            'gender' => 'male',
        ]);

        // Добавим изображения
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

        // Добавим варианты (размер, цвет, количество)
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
Step into a world of elegance and whimsy with our exclusive "Ballerina Cappuccina" T-shirt from IgestShop. This vibrant tee features a stunning front print of a graceful ballerina with a cappuccino cup for a head—complete with beautifully detailed latte art. Above the design, our IgestShop logo is proudly displayed in bold white font.
Turn the shirt around, and you'll find the phrase “Ballerina Cappuccina” stylishly printed across the upper back, celebrating the charm and uniqueness of this limited-edition piece. Crafted from high-quality, soft cotton fabric, this T-shirt offers comfort and style in equal measure—perfect for creative souls, coffee lovers, and fashion-forward individuals.
EOT,
            'price' => 69.99,
            'final_price' => 69.99,
            'is_discount' => false,
            'category' => 'hoodie',
            'gender' => 'male',
        ]);

        // Добавим изображения
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

        // Добавим варианты (размер, цвет, количество)
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
Step into a world of elegance and whimsy with our exclusive "Ballerina Cappuccina" T-shirt from IgestShop. This vibrant tee features a stunning front print of a graceful ballerina with a cappuccino cup for a head—complete with beautifully detailed latte art. Above the design, our IgestShop logo is proudly displayed in bold white font.
Turn the shirt around, and you'll find the phrase “Ballerina Cappuccina” stylishly printed across the upper back, celebrating the charm and uniqueness of this limited-edition piece. Crafted from high-quality, soft cotton fabric, this T-shirt offers comfort and style in equal measure—perfect for creative souls, coffee lovers, and fashion-forward individuals.
EOT,
            'price' => 69.99,
            'final_price' => 69.99,
            'is_discount' => false,
            'category' => 'hoodie',
            'gender' => 'male',
        ]);

        // Добавим изображения
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

        // Добавим варианты (размер, цвет, количество)
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
Step into a world of elegance and whimsy with our exclusive "Ballerina Cappuccina" T-shirt from IgestShop. This vibrant tee features a stunning front print of a graceful ballerina with a cappuccino cup for a head—complete with beautifully detailed latte art. Above the design, our IgestShop logo is proudly displayed in bold white font.
Turn the shirt around, and you'll find the phrase “Ballerina Cappuccina” stylishly printed across the upper back, celebrating the charm and uniqueness of this limited-edition piece. Crafted from high-quality, soft cotton fabric, this T-shirt offers comfort and style in equal measure—perfect for creative souls, coffee lovers, and fashion-forward individuals.
EOT,
            'price' => 69.99,
            'final_price' => 69.99,
            'is_discount' => false,
            'category' => 'hoodie',
            'gender' => 'male',
        ]);

        // Добавим изображения
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

        // Добавим варианты (размер, цвет, количество)
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
Step into a world of elegance and whimsy with our exclusive "Ballerina Cappuccina" T-shirt from IgestShop. This vibrant tee features a stunning front print of a graceful ballerina with a cappuccino cup for a head—complete with beautifully detailed latte art. Above the design, our IgestShop logo is proudly displayed in bold white font.
Turn the shirt around, and you'll find the phrase “Ballerina Cappuccina” stylishly printed across the upper back, celebrating the charm and uniqueness of this limited-edition piece. Crafted from high-quality, soft cotton fabric, this T-shirt offers comfort and style in equal measure—perfect for creative souls, coffee lovers, and fashion-forward individuals.
EOT,
            'price' => 69.99,
            'final_price' => 69.99,
            'is_discount' => false,
            'category' => 'hoodie',
            'gender' => 'male',
        ]);

        // Добавим изображения
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

        // Добавим варианты (размер, цвет, количество)
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
Step into a world of elegance and whimsy with our exclusive "Ballerina Cappuccina" T-shirt from IgestShop. This vibrant tee features a stunning front print of a graceful ballerina with a cappuccino cup for a head—complete with beautifully detailed latte art. Above the design, our IgestShop logo is proudly displayed in bold white font.
Turn the shirt around, and you'll find the phrase “Ballerina Cappuccina” stylishly printed across the upper back, celebrating the charm and uniqueness of this limited-edition piece. Crafted from high-quality, soft cotton fabric, this T-shirt offers comfort and style in equal measure—perfect for creative souls, coffee lovers, and fashion-forward individuals.
EOT,
            'price' => 69.99,
            'final_price' => 69.99,
            'is_discount' => false,
            'category' => 'hoodie',
            'gender' => 'female',
        ]);

        // Добавим изображения
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

        // Добавим варианты (размер, цвет, количество)
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
Step into a world of elegance and whimsy with our exclusive "Ballerina Cappuccina" T-shirt from IgestShop. This vibrant tee features a stunning front print of a graceful ballerina with a cappuccino cup for a head—complete with beautifully detailed latte art. Above the design, our IgestShop logo is proudly displayed in bold white font.
Turn the shirt around, and you'll find the phrase “Ballerina Cappuccina” stylishly printed across the upper back, celebrating the charm and uniqueness of this limited-edition piece. Crafted from high-quality, soft cotton fabric, this T-shirt offers comfort and style in equal measure—perfect for creative souls, coffee lovers, and fashion-forward individuals.
EOT,
            'price' => 69.99,
            'final_price' => 69.99,
            'is_discount' => false,
            'category' => 'hoodie',
            'gender' => 'female',
        ]);

        // Добавим изображения
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

        // Добавим варианты (размер, цвет, количество)
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
Step into a world of elegance and whimsy with our exclusive "Ballerina Cappuccina" T-shirt from IgestShop. This vibrant tee features a stunning front print of a graceful ballerina with a cappuccino cup for a head—complete with beautifully detailed latte art. Above the design, our IgestShop logo is proudly displayed in bold white font.
Turn the shirt around, and you'll find the phrase “Ballerina Cappuccina” stylishly printed across the upper back, celebrating the charm and uniqueness of this limited-edition piece. Crafted from high-quality, soft cotton fabric, this T-shirt offers comfort and style in equal measure—perfect for creative souls, coffee lovers, and fashion-forward individuals.
EOT,
            'price' => 69.99,
            'final_price' => 69.99,
            'is_discount' => false,
            'category' => 'hoodie',
            'gender' => 'female',
        ]);

        // Добавим изображения
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

        // Добавим варианты (размер, цвет, количество)
        $variants = [
            ['size' => 'S', 'color_id' => $colorPurple->id, 'amount' => 5],
            ['size' => 'M', 'color_id' => $colorPurple->id, 'amount' => 4],
            ['size' => 'XL', 'color_id' => $colorPurple->id, 'amount' => 3],
        ];

        foreach ($variants as $variant) {
            $product->variants()->create($variant);
        }

        $this->command->info('Sample product created successfully.');
    }
}

