<?php
namespace common\supports;

use Carbon\Carbon;
use yii\i18n\Formatter;

class MyFormatter extends Formatter
{
    public function asExceldate($value, $format = null)
    {
        $expType = request('export_type');
        if($value && in_array($expType, ['Excel2007', 'Excel5', 'Xlsx', 'Xls'])){
            try {
                $date = Carbon::createFromFormat('d/m/Y', $value);
            } catch (\Exception $e){
                $date = Carbon::createFromFormat('Y-m-d', $value);
            }

            return \PHPExcel_Shared_Date::PHPToExcel($date->timestamp);
        }

        return $value;
    }
}