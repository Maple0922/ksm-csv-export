<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CSVImport;
use App\Exports\CSVExport;
use Illuminate\Support\Facades\Log;

class CSVExportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csv:export {filePath}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'CSVの追加書き込み･出力';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $filePath = $this->argument('filePath');

        $importFile = fopen($filePath, "r");

        while ($line = fgetcsv($importFile)) {
            if (!is_numeric($line[0])) {
                continue;
            }
            $importline = [
                'number' => $line[0],
                'area' => $line[1],
                'xm' => $line[2],
                'ym' => $line[3],
                'major' => $line[4],
                'minor' => $line[5],
                'angle' => $line[6],
                'slice' => $line[7]
            ];
            $data_array[] = $importline;
        }

        for ($index = 0; $index < count($data_array); $index++) {
            $data_array[$index] += [
                'time' => self::calcTime($index, $data_array),
                'speed' => self::calcSpeed($index, $data_array),
                'θv' => self::calcThetaV($index, $data_array),
                'θt' => self::calcThetaT($index, $data_array),
                'Δθ' => self::calcDeltaTheta($index, $data_array),
                '64a' => 2 / 3,
                '83a' => 3 / 8,
                '122a' => 2 / 12,
            ];
        }

        $data_array[0] += [
            'Δθ average' => self::calcDeltaThetaAverage($data_array)
        ];

        $head = [
            "", "Area", "XM", "YM", "Major", "Minor", "Angle", "Slice", "time", "speed", "theta v", "theta t", "delta theta", "64a", "83a", "122a", "delta theta average"
        ];

        $tmp = explode("/", $filePath);
        $fileName = $tmp[count($tmp) - 1];
        $dirName = $tmp[count($tmp) - 2];

        $file = fopen('csv_export/' .  $dirName . '/' . $fileName, "w");
        fputcsv($file, $head);
        foreach ($data_array as $data) {
            fputcsv($file, $data);
        }
        fclose($file);
    }

    private static function calcTime($index, $data_array)
    {
        $slice = $data_array[$index]['slice'];
        $time = ($slice - 1) / 30;
        return $time;
    }

    private static function calcSpeed($index, $data_array)
    {
        if ($index >= 99) {
            return '-';
        }

        $xm_1 = $data_array[$index]['xm'];
        $xm_2 = $data_array[$index + 1]['xm'];
        $ym_1 = $data_array[$index]['ym'];
        $ym_2 = $data_array[$index + 1]['ym'];
        $time = self::calcTime($index + 1, $data_array);


        $speed = sqrt(pow($xm_2 - $xm_1, 2) + pow($ym_2 - $ym_1, 2)) / $time;

        return $speed;
    }

    private static function calcThetaV($index, $data_array)
    {
        if ($index >= 99) {
            return '-';
        }

        $xm_1 = $data_array[$index]['xm'];
        $xm_2 = $data_array[$index + 1]['xm'];
        $ym_1 = $data_array[$index]['ym'];
        $ym_2 = $data_array[$index + 1]['ym'];

        $thetaV = atan2($ym_2 - $ym_1, $xm_2 - $xm_1);
        return $thetaV;
    }

    private static function calcThetaT($index, $data_array)
    {
        $angle = $data_array[$index]['angle'];
        $thetaT = (pi() * $angle) / 180 - pi() / 2;
        return $thetaT;
    }

    private static function calcDeltaTheta($index, $data_array)
    {
        if ($index >= 99) {
            return '-';
        }
        $thetaT = self::calcThetaT($index, $data_array);
        $thetaV = self::calcThetaV($index, $data_array);
        $deltaTheta = $thetaT - $thetaV;

        return $deltaTheta;
    }

    private static function calcDeltaThetaAverage($data_array)
    {
        $sum = 0;
        foreach ($data_array as $data) {
            $sum += $data['number'] === '100' ? 0 : $data['Δθ'];
        }

        return $sum / 99;
    }
}
