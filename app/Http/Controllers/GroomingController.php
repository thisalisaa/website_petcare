namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;

class GroomingController extends Controller
{
    public function index()
    {
        $groomings = Grooming::all();
        return view('grooming.index', compact('groomings'));
    }

    public function create()
    {
        return view('grooming.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'foto_produk' => 'required|image|mimes:jpeg,png,jpg,gif',
            'nama_produk' => 'required',
            'deskripsi' => 'required',
            'kategori' => 'required|array',
            'kategori.*.nama' => 'required',
            'kategori.*.harga' => 'required|numeric',
            'kategori.*.diskon' => 'numeric',
        ]);

        // Simpan foto_produk
        $foto_produk = $request->file('foto_produk')->store('produk');

        // Simpan data produk
        $grooming = Grooming::create([
            'foto_produk' => $foto_produk,
            'nama_produk' => $request->nama_produk,
            'deskripsi' => $request->deskripsi,
        ]);

        // Simpan kategori
        foreach ($request->kategori as $kategoriData) {
            $kategori = new Kategori([
                'nama' => $kategoriData['nama'],
                'harga' => $kategoriData['harga'],
                'diskon' => $kategoriData['diskon'] ?? 0,
                'harga_final' => $kategoriData['harga'] - ($kategoriData['harga'] * ($kategoriData['diskon'] / 100)),
            ]);

            $grooming->kategoris()->save($kategori);
        }

        return redirect()->route('grooming.index')->with('success', 'Produk Grooming berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $grooming = Grooming::findOrFail($id);
        return view('grooming.show', compact('grooming'));
    }

    public function edit(string $id)
    {
        $grooming = Grooming::findOrFail($id);
        return view('grooming.edit', compact('grooming'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'foto_produk' => 'image|mimes:jpeg,png,jpg,gif',
            'nama_produk' => 'required',
            'deskripsi' => 'required',
            'kategori' => 'required|array',
            'kategori.*.nama' => 'required',
            'kategori.*.harga' => 'required|numeric',
            'kategori.*.diskon' => 'numeric',
        ]);

        $grooming = Grooming::findOrFail($id);

        // Update foto_produk jika ada file baru
        if ($request->hasFile('foto_produk')) {
            $foto_produk = $request->file('foto_produk')->store('produk');
            $grooming->update(['foto_produk' => $foto_produk]);
        }

        $grooming->update([
            'nama_produk' => $request->nama_produk,
            'deskripsi' => $request->deskripsi,
        ]);

        // Hapus kategori lama dan buat yang baru
        $grooming->kategoris()->delete();
        foreach ($request->kategori as $kategoriData) {
            $kategori = new Kategori([
                'nama' => $kategoriData['nama'],
                'harga' => $kategoriData['harga'],
                'diskon' => $kategoriData['diskon'] ?? 0,
                'harga_final' => $kategoriData['harga'] - ($kategoriData['harga'] * ($kategoriData['diskon'] / 100)),
            ]);

            $grooming->kategoris()->save($kategori);
        }

        return redirect()->route('grooming.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $grooming = Grooming::findOrFail($id);
        $grooming->delete();

        return redirect()->route('grooming.index')->with('success', 'Produk berhasil dihapus.');
    }
}
