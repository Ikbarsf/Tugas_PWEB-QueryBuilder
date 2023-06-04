<?php


namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Http\Controllers\RoleController;


class AdminProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin']);
    }

    private $param;
    public function index()
    {
        try {
            $this->param['getProducts'] = \DB::table('product_tables')
                                        ->select('product_tables.*')
                                        ->get();
                return view('admin.pages.list', $this->param);
            } catch (\Exception $e) {
                return redirect()->back()->withError($e->getMessage());
            } catch (\Illuminate\Database\QueryException $e) {
                return redirect()->back()->withError('Terjadi kesalahan pada database', $e->getMessage());
            }
    }

    public function add()
    {
        try {
            $this->param['getProduct'] = \DB::table('product_tables')
                                        ->select('product_tables.*')
                                        ->get();
            return view('admin.pages.add', $this->param);
        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->withError('Terjadi kesalahan pada database', $e->getMessage());
        }
    }

    public function store(Request $request){
        $this->validate($request,
            [
                'product_name' => 'required',
                'price' => 'required',
                'quantity' => 'required',
                'description' => 'required',
            ],
            [
                'required' => 'Form harus diisi.',
            ],
        );
        // dd($request);
        try {
            $date = date('H-i-s');
            $random = \Str::random(5);

            $product = new Product;    //eloquen
            $product->product_name = $request->product_name;
            $product->price = $request->price;
            $product->quantity = $request->quantity;
            $product->expired_date = $request->expired_date;
            $product->description = $request->description;

        
            if ($request->file('avatar')) {
                $request->file('avatar')->move('image/upload/course/thumbnail', $date.$random.$request->file('avatar')->getClientOriginalName());
                $product->thumbnail_image = $date.$random.$request->file('avatar')->getClientOriginalName();
            } else {
                $product->thumbnail_image = 'default.png';
            }
            $product->save(); 

            return redirect('/admin/product/list-product')->withStatus('Berhasil menambah produk baru');

        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->withError('Terjadi kesalahan pada database', $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $this->param['getProductDetail'] = Product::find($id);
            $this->param['getProductCategories'] = ProductCategory::all();
            return view('mitra.pages.barang.edit', $this->param);
        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->withError('Terjadi kesalahan pada database', $e->getMessage());
        }
    }

    public function update(Request $request, $id){
        try {
            $this->validate($request,
                [
                    'product_name' => 'required',
                    'harga' => 'required',
                    'quantity' => 'required',
                    'product_category_id' => 'required',
                    'description' => 'required',
                ],
                [
                    'required' => ':attribute harus diisi.',
                ],
            );
            $date = date('H-i-s');
            $random = \Str::random(5);

            $product = Product::find($id);
            $product->product_name = $request->product_name;
            $product->harga = $request->harga;
            $product->quantity = $request->quantity;
            $product->expired_date = $request->expired_date;
            $product->category_id = $request->product_category_id;
            $product->description = $request->description;
            $product->mitra_id = $request->user()->id;

            if ($request->file('avatar')) {
                $request->file('avatar')->move('image/upload/course/thumbnail', $date.$random.$request->file('avatar')->getClientOriginalName());
                $product->thumbnail_image = $date.$random.$request->file('avatar')->getClientOriginalName();
            } else {
                $product->thumbnail_image = 'default.png';
            }
            $product->save();

            return redirect('/back-mitra/E-Commers/list-product')->withStatus('Berhasil memperbarui data produk');

        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->withError('Terjadi kesalahan pada database', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            Product::findOrFail($id)->delete();
            return redirect('/admin/product/list-product')->withStatus('Berhasil menghapus data.');
        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->withError('Terjadi kesalahan pada database', $e->getMessage());
        }
    }
}
