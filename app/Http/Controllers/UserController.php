<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\View;
use App\Models\User;

class UserController extends Controller
{
    public function userListView(){
        return view('user-list_page');
    }

    public function userTable(){
        if(request()->ajax()){
            return datatables()->of(User::select('*'))
            ->make(true);
        }
        return view('user-list_page');
    }

    public function exportUsers(){
        $dateStamp = getdate();
        $fileNameDate = $dateStamp['weekday'] . ' ' . $dateStamp['month'].  ' ' . $dateStamp['mday'].  ' ' . $dateStamp['year'];
        $fileName = $fileNameDate.'_'.'Users Table List.csv';
        $users = User::all();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'Name', 'Email', 'Address', 'Phone Number', 'Created At', 'Updated At']; // Adjust the columns as needed

        $callback = function() use($users, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id, 
                    $user->name, 
                    $user->email, 
                    $user->address,
                    $user->phone,
                    $user->created_at,
                    $user->updated_at
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);

    }

    public function importUsers(Request $request){
        $file = $request->file('importUsers');
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
            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'role' => 'user',
                    'address' => $data['address'],
                    'phone' => $data['phone'],
                    'password' => bcrypt('password') // Set a default password or handle it as needed
                ]
            );
        }

        fclose($file);

        return response()->json(['success' => 'Users imported successfully.']);

    }
}
