<?php
class SearchResultsProvider {
    private $con, $username;

    public function __construct($con, $username) {
        $this->con = $con;
        $this->username = $username;
    }
    public function getResults($inputText){
        $entities = EntityProvider::getSearchEntities($this->con, $inputText);
        $html = "<div class='previewCategories noScroll'>";
        $html .= $this->getResultHtml($entities);
        return $html . "</div>";
    }
    private function getResultHtml($entities) {
        if (sizeof($entities) == 0) {
            return "<div class='category'>
                        <h1>Results: match 0</h1>
                    </div>";
        }

        $entitiesHtml = "";
        $previewProvider = new PreviewProvider($this->con, $this->username);
        foreach($entities as $entity) {
            $entitiesHtml .= $previewProvider->createEntityPreviewSquare($entity);
        }

        return "<div class='category'>
                    <h1>Results</h1>
                    <div class='entities'>
                        $entitiesHtml
                    </div>
                </div>";
    }
}
?>