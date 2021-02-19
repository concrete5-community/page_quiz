<?php   
namespace Concrete\Package\PageQuiz;

use AssetList;
use Package;
use BlockType;

class Controller extends Package
{
    protected $pkgHandle = 'page_quiz';
    protected $appVersionRequired = '5.7.4';
    protected $pkgVersion = '1.0';

    public function getPackageName()
    {
        return t('Page Quiz');
    }

    public function getPackageDescription()
    {
        return t('Add a simply quiz to your page');
    }

    public function on_start()
    {
        $this->registerAssets();
    }

    protected function registerAssets()
    {
        $al = AssetList::getInstance();
        $al->register('javascript', 'page-quiz', 'js/page-quiz.js', array(), $this->pkgHandle);
    }

    public function install()
    {
        $pkg = parent::install();

        $this->installEverything($pkg);
    }

    public function upgrade()
    {
        $pkg = parent::getByHandle($this->pkgHandle);

        $this->installEverything($pkg);
    }

    protected function installEverything($pkg)
    {
        $this->installBlockTypes($pkg);
    }

    /**
     * @param Package $pkg
     */
    protected function installBlockTypes($pkg)
    {
        $bts = array(
            'page_quiz',
        );

        foreach ($bts as $btHandle) {
            if (!BlockType::getByHandle($btHandle)) {
                BlockType::installBlockType($btHandle, $pkg);
            }
        }
    }
}
