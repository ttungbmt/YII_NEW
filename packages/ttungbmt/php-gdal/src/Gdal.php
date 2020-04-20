<?php

namespace ttungbmt\gdal;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class Gdal
{
    protected $env;

    protected $connection;
    /**
     * @var string
     */
    protected $command = '';

    /**
     * @var string{}
     */
    protected $options;

    /**
     * @param array $env
     */
    public function __construct($env = [])
    {
        $this->env = $env;
        $this->options = new Options();
        $this->command = new Command();
    }

    public function ogr2ogr()
    {

    }

    public function translate($src, $dst, $options = [])
    {
        $this->command
            ->clear()
            ->add(
                'gdal_translate',
//            [
//                '-of' => 'JPEG',
//                '-co' => 'JPEG_QUALITY=75',
//            ],
                $options,
                $src,
                $dst
            );
        return $this;
    }

    public function o4w_env()
    {
        $this->command->add(['o4w_env']);
        $this->run();
    }

    public function calc($input, $output, $expr)
    {
        $this->command
            ->clear()
            ->add(
                ['o4w_env', '&&'],
                'gdal_calc',
                $input,
                ['--calc' => '"' . $expr . '"'],
                ['--outfile' => $output],
                ['--type' => 'Float32']
            );
        return $this;
    }

    public function getCommand()
    {
        return $this->command;
    }

    public function setCommand($value)
    {
        return $value;
    }

    public function gdalinfo(string $source, array $params = [])
    {
        $this->command
            ->clear()
            ->add(
                'gdalinfo',
                $params,
                $source
            );
        return $this;
    }

    public function rasterToPgSQL($source, $table)
    {
        $this->connection = trans('{{driver}} postgresql://{{username}}:{{password}}@{{host}}:{{port}}/{{db}}', [
            'driver' => 'psql',
            'username' => 'postgres',
            'password' => 'postgres',
            'host' => 'localhost',
            'port' => '5432',
            'db' => 'ql_hanhan',
        ]);

        $this
            ->command
            ->clear()
            ->add('raster2pgsql', [
                '-s' => '4326',
                '-I', '-C', '-M' => $source, '-F', '-t' => '250x250',
                'public.' . $table,
                '|',
                $this->connection
            ]);

        return $this;
    }

    public function shpToPgSQL()
    {

    }

    public function exportPgSQl()
    {

    }

    public function ogrinfo(string $source, array $layers = [], array $opts = [])
    {
        $options = $opts ? $opts : ['-al', '-so'];
        $this->command->clear()->add(
            'ogrinfo',
            $opts,
            $source,
            implode(' ', $layers)
        );

        return $this;
    }

    public function template()
    {

    }

    public function setConnection()
    {

    }
//
//    /**
//     * @return string
//     */
//    protected function setCommand(): string
//    {
////        $options = '';
////        if ($this->_options->helpGeneral === true) {
////            $options .= ' --help-general';
////        }
////        if ($this->_options->ro === true) {
////            $options .= ' -ro';
////        }
////        if ($this->_options->q === true) {
////            $options .= ' -q';
////        }
////        if (!empty($this->_options->where)) {
////            $options .= sprintf(
////                ' -where %s',
////                escapeshellarg($this->_options->where)
////            );
////        }
////        if (!empty($this->_options->spat)) {
////            $options .= sprintf(
////                ' -spat %f %f %f %f',
////                $this->_options->spat[0],
////                $this->_options->spat[1],
////                $this->_options->spat[2],
////                $this->_options->spat[3]
////            );
////        }
////        if (!empty($this->_options->geomfield)) {
////            $options .= sprintf(
////                ' -geomfield %s',
////                escapeshellarg($this->_options->geomfield)
////            );
////        }
////        if (!empty($this->_options->fid)) {
////            $options .= sprintf(
////                ' -fid %s',
////                $this->_options->fid
////            );
////        }
////        if (!empty($this->_options->sql)) {
////            $options .= sprintf(
////                ' -sql %s',
////                escapeshellarg($this->_options->sql)
////            );
////        }
////        if (!empty($this->_options->dialect)) {
////            $options .= sprintf(
////                ' -dialect %s',
////                escapeshellarg($this->_options->dialect)
////            );
////        }
////        if ($this->_options->al === true) {
////            $options .= ' -al';
////        }
////        if ($this->_options->rl === true) {
////            $options .= ' -rl';
////        }
////        if ($this->_options->so === true) {
////            $options .= ' -so';
////        }
////        if (!empty($this->_options->fields)) {
////            $options .= sprintf(
////                ' -fields %s',
////                escapeshellarg($this->_options->fields)
////            );
////        }
////        if (!empty($this->_options->geom)) {
////            $options .= sprintf(
////                ' -geom %s',
////                escapeshellarg($this->_options->geom)
////            );
////        }
////        if ($this->_options->formats === true) {
////            $options .= ' --formats';
////        }
////        if ($this->_options->nomd === true) {
////            $options .= ' -nomd';
////        }
////        if ($this->_options->listmdd === true) {
////            $options .= ' -listmdd';
////        }
////        if (!empty($this->_options->mdd)) {
////            $options .= sprintf(
////                ' -mdd %s',
////                escapeshellarg($this->_options->mdd)
////            );
////        }
////        if ($this->_options->nocount === true) {
////            $options .= ' -nocount';
////        }
////        if ($this->_options->noextent === true) {
////            $options .= ' -noextent--';
////        }
////
////        if (!empty($this->_options->oo) && is_array($this->_options->oo)) {
////            foreach ($this->_options->oo as $name => $value) {
////                $options .= sprintf(
////                    ' -oo %s',
////                    escapeshellarg(sprintf('%s=%s', $name, $value))
////                );
////            }
////        }
////
////        $this->_command = sprintf(
////            'ogrinfo %s %s %s',
////            $options,
////            preg_match('/^[a-z]{2,}:/i', $this->_source) === 1 ? $this->_source : escapeshellarg($this->_source),
////            implode(' ', $this->_layers)
////        );
//
//        return $this->command;
//    }

    /**
     * @param string $name Option name.
     * @param mixed $value Option value.
     *
     * @return void
     */
    public function setOption(string $name, $value = true): void
    {
        $this->options->{$name} = $value;

//        $this->_setCommand();
    }

    /**
     * @param callable|null $callback
     * @param array $env An array of additional env vars to set when running the process
     *
     * @return string
     * @throws ProcessFailedException if the process is not successful.
     *
     */
    public function run(?callable $callback = null, array $env = []): string
    {
        $process = new Process((string)$this->command);
        $process->mustRun($callback, array_merge($this->env, $env));

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $process->getOutput();
    }

}