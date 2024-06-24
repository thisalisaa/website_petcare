namespace App\Models;
use App\Models\Grooming;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;
    protected $table = 'kategori';
    protected $fillable = ['grooming_id', 'nama', 'harga', 'diskon', 'harga_final'];

    public function grooming()
    {
        return $this->belongsTo(Grooming::class);
    }
}
