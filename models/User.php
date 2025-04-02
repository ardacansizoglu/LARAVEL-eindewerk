namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
use HasApiTokens, Notifiable;

protected $fillable = [
'name',
'email',
'password',
];

protected $hidden = [
'password',
'remember_token',
];

protected $casts = [
'email_verified_at' => 'datetime',
];

/**
* Define the many-to-many relationship with the Product model for favorites.
*/
public function favorites(): BelongsToMany
{
return $this->belongsToMany(Product::class, 'favorites', 'user_id', 'product_id');
}

/**
* Define the many-to-many relationship with the Product model for the shopping cart.
*/
public function cart(): BelongsToMany
{
return $this->belongsToMany(Product::class, 'shopping_cart', 'user_id', 'product_id')
->withPivot('quantity', 'size')
->withTimestamps();
}
}