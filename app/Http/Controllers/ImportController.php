<?php

namespace App\Http\Controllers;
use Session;
use \DB;
use Validator;
use App\UserBlockcode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;
class ImportController extends Controller
{



    public function auto_import_files()
    {
        $exist_blockcodes = DB::table('voter_list_blockcode_info')->select('blockcode')
            ->where('scanned', 'DONE')
            ->where('deskewed', 'DONE')
            ->where('converted', 'DONE')
            ->where('qa_converted_file', 'PENDING')
            ->where('uploaded', 'PENDING')
            ->get()->toArray();


        if (empty($exist_blockcodes)) {
            return response()->json([
                'success' => false,
                'message' => 'No pending blockcodes found.',
            ], 200);
        }

        $csvFolder = 'C:/vis-system/blockcode-files/';

        $totalInserted = 0;
        $totalSkipped  = 0;
        $fileResults   = [];

        set_time_limit(0);
        ini_set('memory_limit', '10240M');

        DB::beginTransaction();

        try {

            foreach ($exist_blockcodes as $blockcodeObj) {

                $blockcode = $blockcodeObj->blockcode;
                $csvPath   = $csvFolder . $blockcode . '.csv';
                $fileName  = $blockcode . '.csv';

                // Check if CSV file exists in the folder
                if (!file_exists($csvPath)) {
                    $fileResults[] = [
                        'file'     => $fileName,
                        'inserted' => 0,
                        'skipped'  => 0,
                        'note'     => 'CSV file not found in folder',
                    ];
                    continue;
                }

                // Skip if already inserted
                $exist_data = DB::table('voter_list_voters_info_temp')
                    ->where('blockcode', $blockcode)
                    ->first();

                if (!empty($exist_data)) {
                    $fileResults[] = [
                        'file'     => $fileName,
                        'inserted' => 0,
                        'skipped'  => 0,
                        'note'     => 'Already exists in database',
                    ];
                    continue;
                }

                // Open CSV
                $handle = fopen($csvPath, 'r');
                if ($handle === false) {
                    $fileResults[] = [
                        'file' => $fileName,
                        'note' => 'Could not open CSV file',
                    ];
                    continue;
                }

                $headerRow = fgetcsv($handle);
                if (empty($headerRow)) {
                    fclose($handle);
                    continue;
                }

                $headers     = array_map(fn($h) => strtolower(trim((string) $h)), $headerRow);
            $headers[0]  = ltrim($headers[0], "\xef\xbb\xbf");
            $headerCount = count($headers);

            $insertData = [];
            $skipped    = 0;
            $now        = now()->toDateTimeString();

            while (($row = fgetcsv($handle)) !== false) {

                // Fix: Address column contains commas causing extra columns
                if (count($row) > $headerCount) {
                    $extra = array_splice($row, $headerCount - 1);
                    $row[] = implode(',', $extra);
                }

                $row  = array_pad($row, $headerCount, null);
                $data = array_combine($headers, $row);

                if (empty(array_filter($data, fn($v) => $v !== null && $v !== ''))) {
                    $skipped++;
                    continue;
                }

                $cnic = $data['cnic'] ?? null;
                if (!$cnic) {
                    $skipped++;
                    continue;
                }

                // Father/Husband logic
                $father_husband      = 'Father';
                $father_husband_name = $data["father's name"] ?? null;

                if (!empty($father_husband_name)) {
                    $firstWord = trim(explode(" ", $father_husband_name)[0]);
                    if (in_array($firstWord, ["زوجه", "زال", "زوجہ", "ز وجه"])) {
                        $father_husband = 'Husband';
                    }
                }

                // Gender from last CNIC digit
                $lastDigit = (int) substr($cnic, -1);
                $gender    = ($lastDigit % 2 === 0) ? "FEMALE" : "MALE";

                $insertData[] = [
                    'blockcode'           => $blockcode,
                    'silsila_no'          => $data['house no.'] ?? null,
                    'gharana_no'          => $data['s. no.'] ?? null,
                    'father_husband_name' => $father_husband_name,
                    'father_husband'      => $father_husband,
                    'address'             => $data['address'] ?? null,
                    'name'                => $data['name'] ?? null,
                    'gender'              => $gender,
                    'image_cnic'          => $cnic,
                    'cnic'                => $cnic,
                    'age'                 => $data['age'] ?? null,
                    'created_by'          => 'Auto Imported',
                    'created_at'          => $now,
                ];
            }

            fclose($handle);

            // Bulk insert in chunks
            $inserted = 0;
            foreach (array_chunk($insertData, 500) as $chunk) {
                DB::table('voter_list_voters_info_temp')->insert($chunk);
                $inserted += count($chunk);
            }

            $totalInserted += $inserted;
            $totalSkipped  += $skipped;

            $fileResults[] = [
                'file'     => $fileName,
                'inserted' => $inserted,
                'skipped'  => $skipped,
                'note'     => 'Imported successfully',
            ];
        }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'All files imported successfully.',
                'inserted' => $totalInserted,
                'skipped'  => $totalSkipped,
                'files'    => $fileResults,
            ], 200);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage(),
            ], 500);
        }
    }



}
