<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Post;
use Illuminate\Support\Facades\Storage;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvData = $this->getCsvData(); // Function to read CSV data

        foreach ($csvData as $row) {
            dd('$row', $row);

            Post::create([
                'slug' => $row['slug'],
                'meta_title' => $row['meta_title'],
                'meta_des' => $row['meta_des'],
                'content' => $row['content'],
                'featured_image_name' => $row['featured_image_name'],
                'image_size' => $row['image_size'],
                'image_alt' => $row['image_alt'],
                'share_image' => isset($row['share_image']) ? json_decode($row['share_image'], true) : null, // Handle potential JSON data
                'share_image_video_url' => $row['share_image_video_url'],
                'category_id' => isset($row['category_id']) ? $row['category_id'] : null, // Handle potential missing category ID
                'published' => false, // Assuming data is not published by default
            ]);
        }
    }

    private function getCsvData2()
    {
        // $csvPath = storage_path('app/faqs.csv'); // Assuming CSV is stored in storage/app directory
        $csvPath = "D:/xampp/htdocs/laravel-crud-api/storage/app/faqs.csv";

        if (file_exists($csvPath)) {
            echo "The file '$csvPath' exists.";
          } else {
            echo "The file '$csvPath' does not exist.";
            return [];
          }

          if (($handle = fopen($csvPath, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Process each row of data
                // var_dump($data); // Example: print the data for debugging
            }
        
            fclose($handle);
        } else {
            echo "Could not open the file '$csvPath'.";
            return [];
        }


        //This doesn't work
        // if (!Storage::exists($csvPath)) {
        //     echo($csvPath);
        //     return []; // Handle case where CSV file doesn't exist
        // }


        // dd('file exists');

        $csvData = array_map('str_getcsv', file($csvPath));
        // Skip the header row if it exists
        if (isset($csvData[0]) && array_filter($csvData[0]) !== []) {
            array_shift($csvData);
        }

        dd('$csvData', $csvData);

        return $csvData;
    }

    private function getCsvData()
    {
        $csvPath = "D:/xampp/htdocs/laravel-crud-api/storage/app/faqs.csv";

        if (!file_exists($csvPath)) {
            echo "The file '$csvPath' does not exist.";
            return []; // Handle case where CSV file doesn't exist
        }
        
        // Open the CSV file
        $handle = fopen($csvPath, "r");
        
        if (!$handle) {
            echo "Could not open the file '$csvPath'.";
            return []; // Handle file opening error
        }
        
        // Read the header row
        $headers = fgetcsv($handle, 1000, ","); // Adjust delimiter if needed
        
        // Skip the header row if it exists
        if ($headers !== false) {
            $csvData = [];
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                // Combine headers and data into associative array
                $csvData[] = array_combine($headers, $data);
            }
        } else {
            echo "Error: No header row found in the CSV file.";
            fclose($handle);
            return []; // Handle missing header row
        }
        
        fclose($handle);
        
        // Print or return the CSV data with column names as array indexes
        dd('$csvData', $csvData);
        // return $csvData; // Uncomment this line to return the data
    }
}
