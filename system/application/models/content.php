<?php

/**
 * A model for Content items.
 *
 */
class Content extends Model {
    
    public $id       = '';
    public $parentId = '';
    public $navIsNav = '';
    public $navLog   = '';
    public $navName  = '';
    public $navOrder = '';
    public $navUrl   = '';
    public $navUse   = '';
    public $date     = '';
    public $desc     = '';
    public $header1  = '';
    public $header2  = '';
    public $image    = '';
    public $keywords = '';
    public $name     = '';
    public $style    = '';
    public $tag1     = '';
    public $tag2     = '';
    public $text     = '';
    
    public function Content() {
        parent::Model();
    }
    
    /**
     * Retrieve a specific content item.
     *
     * @param (int or string?) $id the unique identifier of the content item
     */
    public function getContent($id) {
        // TODO
        // Either we use the CRM GUID, or we map a local
        // integer id to the GUID string. Using a map will make 
        // the uri a hell of a lot prettier, but maintaining the
        // map might be too much of a pain.
          
        $content = $this->soap->getContent($id);
        
        return $content; 
    }
    
    /**
     * Retrieve a specific content item by its nav name.
     *
     * @param string $navName the nav name of the content item
     */
    public function getContentByNavName($navName) {
        $content = $this->soap->getContentByNavName($navName);
        
        return $content;
    }
    
    /**
     * Retrieve the content tree of a parent item.
     *
     * @param (int or string?) $id the unique identifier of the parent content item
     */
    public function getContentTree($id) {
        $contentTree = $this->soap->getContentTree($id);
        
        return $contentTree;
    }
    
}

/* End of file content.php */