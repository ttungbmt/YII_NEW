<?php
namespace drought\support;

use common\models\Query;
use Enqueue\Dsn\Dsn;
use Illuminate\Support\Arr;
use Yii;
use yii\db\Expression;

define ('DSN_REGEX', '/^(?P<user>\w+)(:(?P<password>\w+))?@(?P<host>[.\w]+)(:(?P<port>\d+))?\\\\(?P<database>\w+)$/im');

function ParseDsn($dsn)
{
    $result = array
    (
        'user' => '',
        'password' => '',
        'host' => 'localhost',
        'port' => 3306,
        'database' => ''
    );
    if (strlen($dsn) == 0)
    {
        return false;
    }
    if (!preg_match(DSN_REGEX, $dsn, $matches))
    {
        return false;
    }
    if (count($matches) == 0)
    {
        return false;
    }
    foreach ($result as $key => $value)
    {
        if (array_key_exists($key, $matches) and !empty($matches[$key]))
        {
            $result[$key] = $matches[$key];
        }
    }
    return $result;
}

class PgCommand
{
    public function __construct()
    {
        putenv('PATH=' .getenv('PATH').';D:\PROGRAM\PostgreSQL\12\bin');
        putenv('OSGEO4W_ROOT=D:\PROGRAM\OSGeo4W\bin');
    }

    public function setEnv(){

    }

    public function run(){

    }

    public function toCmd($params){
        $arr = collect([]);
        foreach ($params as $k => $p) {
            if(is_numeric($k) || $k === '') {
                $arr->push($p);
            } else {
                $arr->push($k);
                $arr->push($p);
            }
        }
        return $arr->implode(' ');
    }

    public function rasterToDb($file, $table_name){
        $db = Yii::$app->getDb();
        $dsn = "postgresql://{$db->username}:{$db->password}@localhost:5432/ql_hanhan";

        $params = [
            'raster2pgsql',
            '-s' => '4326',
            '-I', '-C', '-M' => $file,'-F', '-t' => '250x250',
            'public.'.$table_name,
            '|', "psql {$dsn}"
        ];

        Yii::$app->db->createCommand("DROP TABLE IF EXISTS {$table_name} CASCADE")->execute();
        Yii::$app->db->createCommand("DROP VIEW IF EXISTS v_{$table_name} CASCADE;")->execute();
        exec($this->toCmd($params),$out,$ret);

        $query = (new Query())->select('x, y, val, geom')->from(['vt' => (new Query())->select(new Expression('(ST_PixelAsPolygons(rast, 1)).*'))->from($table_name)])->andWhere(['<>', 'val', 128]);
        $viewSql = "CREATE VIEW v_{$table_name} AS ".$query->createCommand()->getRawSql();
        Yii::$app->db->createCommand($viewSql)->execute();

        dd($out);
    }

    public function getInfo($file){
        $params = [
            'D:\PROGRAM\OSGeo4W\bin\gdalinfo',
            $file
        ];
        exec($this->toCmd($params),$out,$ret);
        return $out;
    }

    public function calRaster(){
        $params = [
            'call %OSGEO4W_ROOT%\o4w_env.bat', '&&',
            '%OSGEO4W_ROOT%\gdal_calc.py',
            '-A' => 'D:\QL_HANHAN\test\2004.tif',
            '-B' => 'D:\QL_HANHAN\test\2012.tif',
            '-C' => 'D:\QL_HANHAN\test\2015.tif',
            '--outfile' => 'D:\QL_HANHAN\test\result.tif',
            '--calc' => '"((A+B)/2)-C"',
        ];

        exec($this->toCmd($params),$out,$ret);
        dd(Arr::last($out));
    }
}
