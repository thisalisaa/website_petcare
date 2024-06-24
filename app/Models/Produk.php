namespace App\Models;
use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;
    protected $fillable = ['foto_produk', 'nama_produk', 'deskripsi'];

    public function kategoris()
    {
        return $this->hasMany(Kategori::class);
    }
}
