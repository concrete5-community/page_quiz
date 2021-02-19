<?php  
namespace Concrete\Package\PageQuiz\Block\PageQuiz;

use Concrete\Core\Block\BlockController;
use Concrete\Core\Editor\LinkAbstractor;
use Core;

class Controller extends BlockController
{
    protected $btInterfaceWidth = 600;
    protected $btInterfaceHeight = 500;
    protected $btTable = 'btPageQuiz';
    protected $btCacheBlockRecord = true;
    protected $btCacheBlockOutput = true;
    protected $btCacheBlockOutputOnPost = true;
    protected $btCacheBlockOutputForRegisteredUsers = false;
    protected $btWrapperClass = 'ccm-ui';
    protected $btFeatures = array(
        'multimedia',
    );

    public function getBlockTypeDescription()
    {
        return t("Page Quiz");
    }

    public function getBlockTypeName()
    {
        return t("Page Quiz");
    }

    public function view()
    {
        $this->set('entries', $this->getEntries());
    }

    public function add()
    {
        $this->requireAsset('core/file-manager');
        $this->requireAsset('core/sitemap');
        $this->requireAsset('redactor');
    }

    public function edit()
    {
        $this->add();
        $this->set('entries', $this->getEntries());
    }

    public function delete()
    {
        $this->deleteEntries();
        parent::delete();
    }

    public function save($args)
    {
        parent::save($args);

        $this->deleteEntries();

        $app = Core::getFacadeApplication();
        $db = $app->make('database')->connection();
        $count = isset($args['sortOrder']) ? count($args['sortOrder']) : 0;
        $i = 0;
        while ($i < $count) {
            $args['feedback'][$i] = LinkAbstractor::translateTo($args['feedback'][$i]);

            $db->executeQuery(
                "INSERT INTO btPageQuizEntries (bID, answer, feedback, isCorrect, sortOrder) values(?,?,?,?,?)",
                array(
                    $this->bID,
                    $args['answer'][$i],
                    $args['feedback'][$i],
                    $args['isCorrect'][$i],
                    $i,
                )
            );
            ++$i;
        }
    }

    /**
     * @param string $outputContent
     */
    public function registerViewAssets($outputContent = '')
    {
        $this->requireAsset('javascript', 'page-quiz');
    }

    /**
     * @param $field
     *
     * @return string
     */
    public function translateFrom($field)
    {
        return LinkAbstractor::translateFrom($field);
    }

    /**
     * @return string
     */
    public function getSearchableContent()
    {
        $content = '';
        $app = Core::getFacadeApplication();
        $db = $app->make('database')->connection();
        $r = $db->fetchAll("SELECT * FROM btPageQuizEntries where bID = ?", array($this->bID));
        foreach ($r as $row) {
            $content .= $row['answer'] . ' ' . $row['feedback'];
        }

        return $content;
    }

    /**
     * @return array
     */
    protected function getEntries()
    {
        $app = Core::getFacadeApplication();
        $db = $app->make('database')->connection();

        return $db->fetchAll("SELECT * from btPageQuizEntries WHERE bID = ? ORDER BY sortOrder", array($this->bID));
    }

    protected function deleteEntries()
    {
        $app = Core::getFacadeApplication();
        $db = $app->make('database')->connection();
        $db->executeQuery("DELETE FROM btPageQuizEntries WHERE bID = ?", array($this->bID));
    }
}
