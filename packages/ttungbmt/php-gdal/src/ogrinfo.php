<?php
namespace ttungbmt\gdal;

class ogrinfo
{
    protected $_options;

    /**
     * @param string   $source Datasource.
     * @param string[] $layers Layers from datasource (optional).
     *
     * @return void
     */
    public function __construct(string $source, $layers = [])
    {
//        $this->_source = $source;
//        $this->_layers = (is_string($layers) ? [$layers] : $layers);
//        $this->_options = new ogrinfo\Options();

        $this->_setCommand();
    }

    protected function _setCommand()
    {

    }

    /**
     * @param string $name  Option name.
     * @param mixed  $value Option value.
     *
     * @return void
     */
    public function setOption(string $name, $value = true): void
    {
        $this->_options->{$name} = $value;

        $this->_setCommand();
    }
}