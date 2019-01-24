<?php
    $query = "SELECT c1.id_comment as id_comment, c1.comment_text as comment_text, c1.comment_date as comment_date, c1.id_node as id_node, u1.login as login, c3.comment_text as reply_text, c3.comment_date as reply_date, u2.login as reply_login, c3.id_comment as reply_id
    FROM Comments c1 LEFT JOIN User u1 on u1.id_user = c1.id_user LEFT JOIN Comments c2 ON c1.id_node = c2.id_comment LEFT JOIN Comments c3 on c3.id_comment = c1.id_reply LEFT JOIN User u2 on u2.id_user = c3.id_user
	 where c1.id_spectacle='$id' order by c1.comment_date;";

    $results = mysqli_query($link, $query);
    $comments = array();
    while ($row = mysqli_fetch_assoc($results)) {
        if(is_null($row['id_node'])) {
            $row['id_node'] = 0;
        }
        $comments[] = $row;   
    }
    mysqli_free_result($results);

    $html = array();
    $root_id = 0;
    foreach ($comments as $comment)
        $children[$comment['id_node']][] = $comment;

    // loop will be false if the root has no children (i.e., an empty comment!)
    $loop = !empty($children[$root_id]);

    // initializing $parent as the root
    $parent = $root_id;
    $parent_stack = array();

    // HTML wrapper for the menu (open)
    $html[] = '<ul class="comments firstComment">';

    if($loop)
    {
        while (( ( $option = each($children[$parent]) ) || ( $parent > $root_id ) )) {
            if ($option === false) {
                $parent = array_pop($parent_stack);
                
                // HTML for comment item containing childrens (close)
                $html[] = '</ul>';
                $html[] = '</li>';
            } elseif (!empty($children[$option['value']['id_comment']])) {
                $keep_track_depth = count($parent_stack);
                $reply_link = '<button type="submit" id="' . $option['value']['id_comment'] . '" class="btn btn-info btn-sm"
                    onClick="sendIdComment(this.id);">Odpowiedz</button>';
                if($isLoggedIn) {
                    if($user_type == "Admin") {
                        $clean_link = '<button type="submit" name="clean_id" class="btn btn-warning btn-sm"
                        onClick="cleanComment(' . $option['value']['id_comment'] . ');return false;";>Wyczyść komentarz</button>';
                    }
                    else {
                        $clean_link = '';
                    }
                }
                $option['value']['login'] = htmlspecialchars($option['value']['login']);
                $option['value']['comment_date'] = htmlspecialchars($option['value']['comment_date']);

                if(empty($option['value']['comment_text'])) {
                    $option['value']['comment_text'] = '<b>Komentarz usunięty przez administratora - złamanie regulaminu.</b>';
                }
                else {
                    $option['value']['comment_text'] = htmlspecialchars($option['value']['comment_text']);
                }

                $html[] = 
                '<li>
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <strong>'. $option['value']['login'] .'</strong>
                        <div class="pull-right">
                            <a href=\'?id='. $id . '&comment='. $option['value']['id_comment'] .'\'>
                                <span class=\'glyphicon glyphicon-link\'></span>
                            </a> 
                            <span class="text-muted">#' . $option['value']['id_comment'] . '</span>
                        </div>
                    </div>
                    <div class="panel-body" id="comment_' . $option['value']['id_comment'] . '">
                        ' . $option['value']['comment_text'] . '
                    </div>
                    <div class="panel-footer clearfix">
                        <small><span class="text-muted">Dodano: ' . $option['value']['comment_date'] . '</span></small>';
                        if($isLoggedIn) {
                            $html[] = '<div class="btn-group pull-right">' .
                            $clean_link . $reply_link . '</div>';
                        }
                        $html[] = '
                    </div>
                </div>
            </li>';
                $html[] = '<ul class="comments" id="node_' . $option['value']['id_comment'] . '">';

    
                array_push($parent_stack, $option['value']['id_node']);
                $parent = $option['value']['id_comment'];
            } else {
                $keep_track_depth = count($parent_stack);
                $reply_link = '<button type="submit" id="' . $option['value']['id_comment'] . '" class="btn btn-info btn-sm"
                    onClick="sendIdComment(this.id);">Odpowiedz</button>';
                if($isLoggedIn) {
                    if($user_type == "Admin") {
                        $clean_link = '<button type="submit" name="clean_id" class="btn btn-warning btn-sm"
                        onClick="cleanComment(' . $option['value']['id_comment'] . ');return false;";>Wyczyść komentarz</button>';
                    }
                    else {
                        $clean_link = '';
                    }
                }
                $option['value']['login'] = htmlspecialchars($option['value']['login']);
                $option['value']['comment_date'] = htmlspecialchars($option['value']['comment_date']);

                $option['value']['reply_login'] = htmlspecialchars($option['value']['reply_login']);


                if(empty($option['value']['comment_text'])) {
                    $option['value']['comment_text'] = '<b>Komentarz usunięty przez administratora - złamanie regulaminu.</b>';
                }
                else {
                    $option['value']['comment_text'] = htmlspecialchars($option['value']['comment_text']);
                }
                if(empty($option['value']['reply_text'])) {
                    $option['value']['reply_text'] = '<b>Komentarz usunięty przez administratora - złamanie regulaminu.</b>';
                }
                else {
                    $option['value']['reply_text'] = htmlspecialchars($option['value']['reply_text']);
                }
              
                // HTML for comment item with no children (aka "leaf")
                $html[] = 
                '<li>
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <strong>'. $option['value']['login'] .'</strong>
                            <div class="pull-right">
                            <a href=\'?id='. $id . '&comment='. $option['value']['id_comment'] .'\'>
                                <span class=\'glyphicon glyphicon-link\'></span>
                            </a> 
                            <span class="text-muted">#' . $option['value']['id_comment'] . '</span>
                        </div>
                        </div>
                        <div class="panel-body">';
                if(!empty($option['value']['reply_login'])) {
                    $html[] = '<blockquote>
                                    <small>' . $option['value']['reply_text'] . '</small>
                                <span class="text-muted">
                                <a href=\'#\' onClick=\'navigateToComment(' . $option['value']['reply_id'] . '); return false;\'><span class=\'glyphicon glyphicon-share-alt\'></span></a> 
                                #'. $option['value']['reply_id'] .', napisał: <b>' . $option['value']['reply_login'] .'</b>, dodano: ' . $option['value']['reply_date'] . '</span>
                                </blockquote>';
                }
                $html[] = '<div id="comment_' . $option['value']['id_comment'] . '">'. $option['value']['comment_text'] . '</div>
                        </div>
                        <div class="panel-footer clearfix">
                            <small><span class="text-muted">Dodano: ' . $option['value']['comment_date'] . '</span></small>';
                            if($isLoggedIn) {
                                $html[] = '<div class="btn-group pull-right">' .
                                $clean_link . $reply_link . '</div>';
                            }
                            $html[] = '
                        </div>
                    </div>
                </li>';
                
            }
        }
    }
    // HTML wrapper for the comment (close)
    $html[] = '</ul>';
    echo implode("\r\n", $html);
    
    
?>