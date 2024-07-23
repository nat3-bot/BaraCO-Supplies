<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use App\Models\Supplies;

class SuppliesController extends Controller
{

    public function productListView(){
        return view('product_list');
    }

    public function loadProducts(Request $request){
        $products = Supplies::all();
        $view = View::make('components.product_card', compact('products'))->render();
        return response()->json(['html' => $view ]);
    }

    public function productPage($id){
        $product = Supplies::find($id);

        return view('product_page', ['product' => $product]);
    }

    public function searchProducts(Request $request){
        $query = $request->input('searchProduct');
        $products = Supplies::where('name', 'LIKE', "%{$query}%")->get();
        return response()->json($products);
    }

    public function getSupplies(){
        if(request()->ajax()){
            return datatables()->of(Supplies::select('*'))
            ->addColumn('photo', function($row){
                $url = asset('storage/photos/' . $row->photo);
                return $url;
            })
            ->addColumn('action','components.supplies-action')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('admin_dashboard');
        
    }

    public function addSupplies(Request $request){
        $productId = $request->id;
    
        // Validate the request, including the photo field
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Adjust max file size as needed
        ]);

        $checkPhoto = Supplies::find($productId);

        if($request->hasFile('photo')){

            if(isset($checkPhoto->photo)){
            Storage::delete('public/photos/'.$checkPhoto->photo);
            }   
            else{
            $photo = $request->file('photo');
            $photoName = time().'_'.$photo->getClientOriginalName();            
            $photo->storeAs('public/photos', $photoName);
            }

        } else {

            $photoName = $checkPhoto->photo; 
            
        }
    
        // Save the data to the database
        $product = Supplies::updateOrCreate(
            ['id' => $productId],
            [
                'photo' => $photoName,
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock
                 
            ]
        );
    
        return response()->json($product);
    }

    public function deleteSupplies(Request $request){
        $deletePhoto = Supplies::find($request->id);
        if($deletePhoto->photo == "no-image.svg"){
            $product = Supplies::where('id',$request->id)->delete();
        }
        else{
            Storage::delete('public/photos/'.$deletePhoto->photo);
            $product = Supplies::where('id',$request->id)->delete();
        }

        return response()->json($product);
    }

    public function editSupplies(Request $request){
        $id = array('id' => $request->id);
        $product = Supplies::where($id)->first();

        return response()->json($product);
    }

    public function importSupplies(Request $request){
        $file = $request->file('importSupplies');
        $filePath = $file->getRealPath();
        
        // Read and process the CSV file
        $file = fopen($filePath, 'r');
        $header = fgetcsv($file);

        while ($columns = fgetcsv($file)) {
            if ($columns[0] == "") {
                continue;
            }
            
            $data = array_combine($header, $columns);

            // Create or update user
            Supplies::updateOrCreate(
                [
                    'photo' => 'no-image.svg',
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'price' => $data['price'],
                    'stock' => $data['stock'],
                ]
            );
        }

        fclose($file);

        return response()->json(['success' => 'Users imported successfully.']);

    }

    public function exportSupplies(){
        $dateStamp = getdate();
        $fileNameDate = $dateStamp['weekday'] . ' ' . $dateStamp['month'].  ' ' . $dateStamp['mday'].  ' ' . $dateStamp['year'];
        $fileName = $fileNameDate.'_'.'Supplies Table List.csv';
        $supply = Supplies::all();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'Name', 'Description', 'Price', 'Stock', 'Created At', 'Updated At']; // Adjust the columns as needed

        $callback = function() use($supply, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($supply as $supplies) {
                fputcsv($file, [
                    $supplies->id, 
                    $supplies->name, 
                    $supplies->description, 
                    $supplies->price,
                    $supplies->stock,
                    $supplies->created_at,
                    $supplies->updated_at
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);

    }
}
