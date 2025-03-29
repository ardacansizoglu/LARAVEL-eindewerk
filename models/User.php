namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Product;

class User extends Authenticatable
{
public function favorites(): BelongsToMany
{
return $this->belongsToMany(Product::class, 'favorites', 'user_id', 'product_id');
}
}