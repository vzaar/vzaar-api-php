<?php
    require 'autoload.php';
    
    
    //
    // Create / Read / Update / Delete
    //
    try {
        
        // create
        
        $params = array('title' => 'playlist from php sdk examples',
                        'category_id' => $category_id);
        
        $plist = VzaarApi\Playlist::create($params);
        echo PHP_EOL.'Created Playlist: ' .$plist->id .' '. $plist->title;
        
        echo PHP_EOL;
        
        //read
        
        $playlist = VzaarApi\Playlist::find($plist->id);
        echo PHP_EOL.'Lookup Playlist: id: ' .$playlist->id .' '. $playlist->title;
        echo PHP_EOL;
        
        // update
        
        echo PHP_EOL.'Updating Playlist: ' .$plist->id .' title: '. $plist->title .', autoplay: ' .($plist->autoplay ? 'true' : 'false');
        $plist->title = 'updated title from php sdk';
        $plist->autoplay = true;
        $plist->save();
        
        echo PHP_EOL.'Updated Playlist: ' .$plist->id .' title: '. $plist->title .', autoplay: ' .($plist->autoplay ? 'true' : 'false');
        
        echo PHP_EOL;
        
        echo PHP_EOL.'Deleting Playlist: ' .$plist->id;
        $plist->delete();
        
        echo PHP_EOL;
        
    } catch(VzaarApi\Exceptions\VzaarException $ve) {
        
        echo $ve->getMessage();
        
    } catch(VzaarApi\Exceptions\VzaarError $verr) {
        
        echo $verr->getMessage();
        
    }
    
    
    //
    // List Playlist: Iterate over playlists
    //
    try {
        
        foreach(VzaarApi\PlaylistsList::each_item() as $playlist) {
            if(isset($playlist->id) & isset($playlist->title)){
                echo PHP_EOL.'List Playlist (iterate): id: ' .$playlist->id .' '. $playlist->title;
            }
        }
        echo PHP_EOL;
        
    } catch(VzaarApi\Exceptions\VzaarException $ve) {
        
        echo $ve->getMessage();
        
    } catch(VzaarApi\Exceptions\VzaarError $verr) {
        
        echo $verr->getMessage();
        
    }
    
    
    //
    // Paginate Playlist
    //
    try {
        
        $paramsList = array('per_page' => '2');
        $paramsList['sort'] = 'created_at';
        
        //this methods gets initial page
        $pager = VzaarApi\PlaylistsList::paginate($paramsList);
        
        foreach($pager as $playlist) {
            if(isset($playlist->id) & isset($playlist->title)){
                echo PHP_EOL.'Paginate Playlists (first): id: ' .$playlist->id .' '. $playlist->title;
            }
        }
        echo PHP_EOL;
        
        if($pager->nextPage() === true) {
            foreach($pager as $playlist) {
                if(isset($playlist->id) & isset($playlist->title)){
                    echo PHP_EOL.'Paginate Playlists (next): id: ' .$playlist->id .' '. $playlist->title;
                }
            }
        }
        echo PHP_EOL;
        
        if($pager->lastPage() === true) {
            foreach($pager as $playlist) {
                if(isset($playlist->id) & isset($playlist->title)){
                    echo PHP_EOL.'Paginate Playlists (last): id: ' .$playlist->id .' '. $playlist->title;
                }
            }
        }
        echo PHP_EOL;
        
        if($pager->previousPage() === true) {
            foreach($pager as $playlist) {
                if(isset($playlist->id) & isset($playlist->title)){
                    echo PHP_EOL.'Paginate Playlists (previous): id: ' .$playlist->id .' '. $playlist->title;
                }
            }
        }
        echo PHP_EOL;
        
        if($pager->firstPage() === true) {
            foreach($pager as $playlist) {
                if(isset($playlist->id) & isset($playlist->title)){
                    echo PHP_EOL.'Paginate Playlists (first): id: ' .$playlist->id .' '. $playlist->title;
                }
            }
        }
        echo PHP_EOL;
        
    } catch(VzaarApi\Exceptions\VzaarException $ve) {
        
        echo $ve->getMessage();
        
    } catch(VzaarApi\Exceptions\VzaarError $verr) {
        
        echo $verr->getMessage();
        
    }
    
    
    
    echo PHP_EOL;
    
?>
