<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Post;
use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Facades\DateTime;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Truncate the posts table before populating it with new data
        Post::query()->truncate();
        Log::info('Truncated posts table before seeding.'); // Log truncation

        $csvData = $this->getCsvData();

        // Check if CSV data is not empty to avoid potential errors
        if (empty($csvData)) {
            echo "No data found in the CSV file.";
            Log::error('No data found in CSV file.'); // Log error
            return;
        }

        foreach ($csvData as $row) {

            // Check if 'id' is numeric and skip if not
            if (!is_numeric($row['id'])) {
                echo "Skipping row: 'id' is not numeric. \n";
                Log::warning('Skipped row: id is not numeric.'); // Log warning
                continue;
            }

            // Slug is not unique issue handling
            // Method2: Skip if slug is empty or already exists
            if (empty($row['slug']) || Post::where('slug', $row['slug'])->exists()) {
                $reason = empty($row['slug']) ? "Empty slug" : "Duplicate slug '" . $row['slug'] . "'";
                echo "Skipping row: $reason. \n";
                Log::warning('Skipped row: ' . $reason); // Log warning
                continue;
            }

            // Handle potential empty share_image value
            $row['share_image'] = isset($row['share_image']) ? json_encode($row['share_image']) : null;
            $row['share_image_name'] = isset($row['share_image_name']) ? json_encode($row['share_image_name']) : null;
            $row['deleted_at'] = isset($row['deleted_at']) && !empty($row['deleted_at']) ? $row['deleted_at'] : null;
            $row['content'] = isset($row['content']) ? $row['content'] : ' '; // Default content if empty

            // Add created_at and updated_at if not present
            $row['created_at'] = $row['created_at'] ?? Carbon::now();
            $row['updated_at'] = $row['updated_at'] ?? Carbon::now();

            $row['created_at'] = $this->parseDateTime($row['created_at']) ?: null;
            $row['updated_at'] = $this->parseDateTime($row['updated_at']) ?: null;
            $row['deleted_at'] = $this->parseDateTime($row['deleted_at']) ?: null;

            Post::create($row);

            // Log successful record creation
            Log::info('Created record with id: ' . $row['id']);
        }

        echo "Seeding completed successfully!";
        Log::info('Seeding completed successfully.'); // Log success
    }

    /**
     * Reads CSV data and returns an array of associative arrays.
     *
     * @return array
     */
    public function getCsvData(): array
    {
        $csvPath = "D:/xampp/htdocs/laravel-crud-api/storage/app/faqs.csv"; // Replace with your actual CSV path

        if (!file_exists($csvPath)) {
            echo "The file '$csvPath' does not exist.";
            return [];
        }

        // Open the CSV file
        $handle = fopen($csvPath, "r");

        if (!$handle) {
            echo "Could not open the file '$csvPath'.";
            return [];
        }

        // Read the header row
        $headers = fgetcsv($handle, 1000, ","); // Adjust delimiter if needed

        // Skip the header row if it exists
        if ($headers !== false) {
            $csvData = [];
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                $data = array_pad($data, count($headers), null); // Pad with null values (adjust default if needed)
                $csvData[] = array_combine($headers, $data);
            }
        } else {
            echo "Error: No header row found in the CSV file.";
            fclose($handle);
            return [];
        }

        fclose($handle);

        return $csvData;
    }

    /**
     * Parses a string as a datetime and returns a DateTime object or null if parsing fails.
     *
     * @param string $dateTimeString
     * @return DateTime|null
     */
    private function parseDateTime(?string $dateTimeString): ?Carbon
    {
        if (empty($dateTimeString)) {
            return null;
        }

        try {
            return Carbon::createFromFormat('Y-m-d H:i:s', $dateTimeString);
        } catch (Exception $e) {
            // Handle potential parsing exceptions (optional)
            // echo "Error parsing datetime: " . $e->getMessage();
            return null;
        }
    }
}
