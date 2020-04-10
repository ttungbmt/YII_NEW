<?php
namespace common\libs\excel;

use PHPExcel_Cell;
use PHPExcel_IOFactory;
use PHPExcel_RichText;
use PHPExcel_Shared_Date;
use PHPExcel_Style_NumberFormat;
use PHPExcel_Worksheet;
use yii\helpers\ArrayHelper;
use PHPExcel;

/**
 * Usage
 * -----
 *
 * @property boolean $setFirstRecordAsKeys to set the first record on excel file to a keys of array per line.
 * @author    Truong Thanh Tung <ttungbmt@gmail.com>
 * @copyright 2018
 * @since     1
 */
class Excel
{
    public $isMultipleSheet = false;

    public $properties;

    public $columns = [];

    public $headers = [];

    public $fileName;

    public $savePath;

    public $format;
    /**
     * @var boolean to set the first record on excel file to a keys of array per line.
     * If you want to set the keys of record column with first record, if it not set, the header with use the alphabet column on excel.
     */
    public $setFirstRecordAsKeys = true;
    /**
     * @var array to unread record by index number.
     */
    public $leaveRecordByIndex = [];
    /**
     * @var array to read record by index, other will leave.
     */
    public $getOnlyRecordByIndex = [];

    public function import($fileName, $startDataRow = 1)
    {
//        $excel = new PHPExcel();
//        $phpExcel = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
//        $phpExcel->setPreCalculateFormulas(true);
//        $phpExcel->save($fileName);

        if (!$this->format) $this->format = PHPExcel_IOFactory::identify($fileName);

        $reader = PHPExcel_IOFactory::createReader($this->format);
        $phpExcel = $reader->load($fileName);

        $sheetCount = $phpExcel->getSheetCount();
        $sheetDatas = [];

        $worksheet = $phpExcel->getActiveSheet();


//        $sheetDatas = $worksheet->toArray(null, true, true, true);
        $sheetDatas = $this->getSheetDatas($worksheet, $startDataRow);

        if ($this->setFirstRecordAsKeys) {
            $sheetDatas = $this->executeArrayLabel($sheetDatas);
        }
        if (!empty($this->getOnlyRecordByIndex)) {
            $sheetDatas = $this->executeGetOnlyRecords($sheetDatas, $this->getOnlyRecordByIndex);
        }
        if (!empty($this->leaveRecordByIndex)) {
            $sheetDatas = $this->executeLeaveRecords($sheetDatas, $this->leaveRecordByIndex);
        }

        return $sheetDatas;
    }

    public function getSheetDatas(PHPExcel_Worksheet $worksheet, $startRow = 1)
    {
//        dd($worksheet->toArray(null, true, true, true));
        $nullValue = null;
        $calculateFormulas = true;
        $formatData = true;
//        $maxRow = $worksheet->getHighestRow();
//        $maxCol = $worksheet->getHighestColumn();
//        $maxColIndex = PHPExcel_Cell::columnIndexFromString($maxCol);
//
//        $returnValue = [];
//        list($rangeStart, $rangeEnd) = PHPExcel_Cell::rangeBoundaries('A1:'.$maxCol.$maxRow);
//        dd($rangeEnd);

        $returnValue = [];
        $isFormatHeader = true;
        $columnLetter = array_keys($worksheet->getColumnDimensions());

        foreach ($worksheet->getRowIterator($startRow) as $r => $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            foreach ($cellIterator as $c => $cell) {
                $value = $cell->getValue();

                if ($r === $startRow) {
                    $key = $isFormatHeader ? str_slug($value, '_') : $value;

                    $this->headers[$key] = [
                        'key'        => $key,
                        'value'      => $value,
                        'coordinate' => $cell->getCoordinate(),
                        'column'     => $cell->getColumn(),
                    ];
                } else {
                    $v = $nullValue;

                    if (!is_null($value)) {
                        if ($value instanceof PHPExcel_RichText) {
                            $v = $value->getPlainText();
                        } else {
                            if ($calculateFormulas) {
                                $v = ($cell->isFormula()) ? $cell->getOldCalculatedValue() : $cell->getCalculatedValue();
                            } else {
                                $v = $value;
                            }
                        }

                        if ($formatData) {
                            $style = $cell->getStyle();
                            $numberFormat = $style->getNumberFormat();

                            if(PHPExcel_Shared_Date::isDateTime($cell) && is_numeric($v)){
//                                dd(PHPExcel_Shared_Date::stringToExcel('2019-06-12'));
//                                dd(PHPExcel_Shared_Date::stringToExcel('2019-06-12'), $v, PHPExcel_Shared_Date::ExcelToPHPObject($v));
                                $v = PHPExcel_Shared_Date::ExcelToPHPObject($v)->format('d/m/Y');
                            } else {
                                $v = PHPExcel_Style_NumberFormat::toFormattedString(
                                    $v,
                                    ($style && $numberFormat) ? $numberFormat->getFormatCode() : PHPExcel_Style_NumberFormat::FORMAT_GENERAL
                                );
                            }
                        }

                        $returnValue[$r][$c] = $v;
                    }

                    $returnValue[$r][$c] = $v;

                }

            };

            $this->removeEmptyRow($returnValue, $r);
        }

        return $returnValue;
    }

    protected function removeEmptyRow(&$returnValue, $r){
        if(
            isset($returnValue[$r]) &&
            collect($returnValue[$r])->filter(function($item){
                $val = trim($item);
                return $val !== null && $val !== '';
            })->isEmpty()
        ){
            unset($returnValue[$r]);
        }
    }


    /**
     * Setting label or keys on every record if setFirstRecordAsKeys is true.
     *
     * @param array $sheetData
     *
     * @return multitype:multitype:array
     */
    public function executeArrayLabel($sheetData)
    {
        $keys = collect($this->headers)
            ->pluck('key', 'column')
            ->filter();

        $new_data = [];

        foreach ($sheetData as $values) {
            $new_data[] = $keys->combine(
                collect($values)->only($keys->keys())
            )->all();
        }

        return $new_data;
    }

    /**
     * Read record with same index number.
     *
     * @param array $sheetData
     * @param array $index
     *
     * @return array
     */
    public function executeGetOnlyRecords($sheetData = [], $index = [])
    {
        foreach ($sheetData as $key => $data) {
            if (!in_array($key, $index)) {
                unset($sheetData[$key]);
            }
        }
        return $sheetData;
    }

    /**
     * Leave record with same index number.
     *
     * @param array $sheetData
     * @param array $index
     *
     * @return array
     */
    public function executeLeaveRecords($sheetData = [], $index = [])
    {
        foreach ($sheetData as $key => $data) {
            if (in_array($key, $index)) {
                unset($sheetData[$key]);
            }
        }
        return $sheetData;
    }

    public function readSheetHeader($fileName, $row = 1) {

        $row_header = $row;


        if (!$this->format) $this->format = PHPExcel_IOFactory::identify($fileName);

        $reader = PHPExcel_IOFactory::createReader($this->format);
        $phpExcel = $reader->load($fileName);

        $worksheet = $phpExcel->getActiveSheet();

        $highestColumn = $worksheet->getHighestColumn();
        $headingsArray = $worksheet->rangeToArray('A'.$row_header.':'.$highestColumn.$row_header, null, true, true, true);

        $header = $headingsArray[$row_header];
        foreach($header as $k => $value) {
            $header[$k] = str_slug($value, '_');
        }

        return $header;
    }

}