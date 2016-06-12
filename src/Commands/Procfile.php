<?php
namespace Chatbox\Heroku\Commands;
use Chatbox\Heroku\Addons\SendgridService;
use Chatbox\Heroku\Config\DatabaseConfig;
use Illuminate\Console\Command;

/**
 * Created by PhpStorm.
 * User: mkkn
 * Date: 2016/02/04
 * Time: 23:48
 */
class Procfile extends Command
{
    use ConfigSetTrait;

    protected $signature = "
        heroku:procifle
        --type
    ";

    protected $description = "create Procfile";

    /**
     * アプリケーション初期化
     * Procfile作成
     * ビルドパック追加
     * ロケール追加
     */
    public function handle(){
        $dir = getcwd();
        $app = $this->option("appname");

        system("heroku git:remote -a $app");
        if($this->option("node")){
            system("heroku buildpacks:add heroku/nodejs");
        }
        system("heroku buildpacks:add heroku/php");

        $procfile = <<<Procfile
web: vendor/bin/heroku-php-apache2 public/
Procfile;
        $this->line("Procfile written to $dir/Procfile");
        file_put_contents($dir."/Procfile",$procfile);

        system("heroku config:set APP_TIMEZONE=Asia/Tokyo");
    }
}

