namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
public function run()
{
Product::create([
'name' => 'Sample Shoe',
'available_sizes' => ['7', '8', '9'],
'price' => 99.99,
'brand_id' => 1,
'description' => 'A sample shoe description.',
'image' => 'sample-shoe.jpg',
]);
}
}